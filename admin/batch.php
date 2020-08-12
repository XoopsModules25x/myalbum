<?php
// ------------------------------------------------------------------------- //
//                      myAlbum-P - XOOPS photo album                        //
//                        <http://www.peak.ne.jp>                           //
// ------------------------------------------------------------------------- //

use Xmf\Module\Admin;
use XoopsModules\Myalbum\{
    CategoryHandler,
    Forms,
    Helper,
    PhotosHandler,
    TextHandler,
    Utility
};
/** @var Helper $helper */
/** @var Admin $adminObject */

require_once __DIR__ . '/admin_header.php';

// GPCS vars
$GLOBALS['submitter'] = \Xmf\Request::getInt('submitter', $my_uid, 'POST');

$cid = \Xmf\Request::getInt('cid', 0);

$GLOBALS['dir4edit']   = isset($_POST['dir']) ? $GLOBALS['myts']->htmlSpecialChars($_POST['dir']) : '';
$GLOBALS['title4edit'] = isset($_POST['title']) ? $GLOBALS['myts']->htmlSpecialChars($_POST['title']) : '';
$GLOBALS['desc4edit']  = isset($_POST['desc']) ? $GLOBALS['myts']->htmlSpecialChars($_POST['desc']) : '';

// reject Not Admin
if (!$isadmin) {
    redirect_header($mod_url, 2, _ALBM_MUSTREGFIRST);
}
/** @var CategoryHandler $catHandler */
$catHandler = $helper->getHandler('Category');
// check Categories exist
$count = $catHandler->getCount();
if ($count < 1) {
    redirect_header(XOOPS_URL . "/modules/$moduleDirName/admin", 2, _ALBM_MUSTADDCATFIRST);
}
/** @var  PhotosHandler $photosHandler */
$photosHandler = $helper->getHandler('Photos');
/** @var  TextHandler $textHandler */
$textHandler = $helper->getHandler('Text');

if (\Xmf\Request::hasVar('submit', 'POST') && '' !== $_POST['submit']) {
    ob_start();

    // Check Directory
    $dir = $GLOBALS['myts']->stripSlashesGPC($_POST['dir']);
    if (empty($dir) || !is_dir($dir)) {
        if (0x2f !== ord($dir)) {
            $dir = "/$dir";
        }
        $prefix = XOOPS_ROOT_PATH;
        while (mb_strlen($prefix) > 0) {
            if (is_dir("$prefix$dir")) {
                $dir = "$prefix$dir";
                break;
            }
            $prefix = mb_substr($prefix, 0, mb_strrpos($prefix, '/'));
        }
        if (!is_dir($dir)) {
            redirect_header('batch.php', 3, _ALBM_MES_INVALIDDIRECTORY . "<br>$dir4edit");
        }
    }
    if ('/' === mb_substr($dir, -1)) {
        $dir = mb_substr($dir, 0, -1);
    }

    $title4save = $GLOBALS['myts']->htmlSpecialChars($_POST['title']);
    $desc4save  = $GLOBALS['myts']->addSlashes($_POST['desc']);

    $date = strtotime($_POST['post_date']);
    if (-1 == $date) {
        $date = time();
    }

    $dir_h = opendir($dir);
    if (false === $dir_h) {
        redirect_header('batch.php', 3, _ALBM_MES_INVALIDDIRECTORY . "<br>$dir4edit");
    }
    $filecount = 1;
    while (false !== ($file_name = readdir($dir_h))) {
        // Skip '.' , '..' and hidden file
        //if (substr($file_name, 0, 1) === '.') {
        if (0 === mb_strpos($file_name, '.')) {
            continue;
        }

        $ext       = mb_substr(mb_strrchr($file_name, '.'), 1);
        $node      = mb_substr($file_name, 0, -mb_strlen($ext) - 1);
        $file_path = "$dir/$node.$ext";

        $title = empty($_POST['title']) ? addslashes($node) : "$title4save $filecount";

        if (is_readable($file_path) && in_array(mb_strtolower($ext), $array_allowed_exts)) {
            if (!in_array(mb_strtolower($ext), $myalbum_normal_exts)) {
                list($w, $h) = getimagesize($file_path);
            } else {
                list($w, $h) = [0, 0];
            }
            $photo = $photosHandler->create();
            $photo->setVar('cid', $cid);
            $photo->setVar('title', $title);
            $photo->setVar('ext', $ext);
            $photo->setVar('res_x', $w);
            $photo->setVar('res_y', $h);
            $photo->setVar('submitter', $submitter);
            $photo->setVar('date', $date);
            $photo->setVar('status', 1);
            $lid = $photosHandler->insert($photo);
            if ($lid) {
                print " &nbsp; <a href='../photo.php?lid=$lid' target='_blank'>$file_path</a>\n";
                copy($file_path, $GLOBALS['photos_dir'] . DS . "$lid.$ext");
                Utility::createThumb($GLOBALS['photos_dir'] . DS . "$lid.$ext", $lid, $ext);
                $text = $textHandler->create();
                $text->setVar('lid', $lid);
                $text->setVar('description', $desc4save);
                $textHandler->insert($text);
                echo _AM_MB_FINISHED . "<br>\n";
            }

            ++$filecount;
        }
    }
    closedir($dir_h);

    if ($filecount <= 1) {
        echo "<p>$dir4edit : " . _ALBM_MES_BATCHNONE . '</p>';
    } else {
        printf('<p>' . _ALBM_MES_BATCHDONE . '</p>', $filecount - 1);
    }

    $result_str = ob_get_contents();
    ob_end_clean();
}

xoops_cp_header();
$adminObject = Admin::getInstance();
$adminObject->displayNavigation(basename(__FILE__));
//myalbum_adminMenu(basename(__FILE__), 4);
$GLOBALS['xoopsTpl']->assign('admin_title', sprintf(_AM_H3_FMT_BATCHREGISTER, $GLOBALS['myalbumModule']->name()));
$GLOBALS['xoopsTpl']->assign('mydirname', $GLOBALS['mydirname']);
$GLOBALS['xoopsTpl']->assign('photos_url', $GLOBALS['photos_url']);
$GLOBALS['xoopsTpl']->assign('thumbs_url', $GLOBALS['thumbs_url']);
$GLOBALS['xoopsTpl']->assign('form', Forms::getAdminFormAdmission());
if (isset($result_str)) {
    $GLOBALS['xoopsTpl']->assign('result_str', $result_str);
}

$GLOBALS['xoopsTpl']->display('db:' . $GLOBALS['mydirname'] . '_cpanel_batch.tpl');

// check $GLOBALS['myalbumModule']
//  myalbum_footer_adminMenu();
require_once __DIR__ . '/admin_footer.php';
