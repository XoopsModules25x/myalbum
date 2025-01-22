<?php
// ------------------------------------------------------------------------- //
//                      myAlbum-P - XOOPS photo album                        //
//                        <http://www.peak.ne.jp>                           //
// ------------------------------------------------------------------------- //

use Xmf\Request;
use XoopsModules\Myalbum\{
    CategoryHandler,
    Forms,
    Helper,
    PhotosHandler,
    Preview,
    TextHandler,
    Utility
};
/** @var Helper $helper */
/** @var CategoryHandler $catHandler */
/** @var PhotosHandler $photosHandler */
/** @var TextHandler $textHandler */

//use XoopsModules\Tag\Helper; //TODO

global $global_perms;

$lid = '';
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/include/get_perms.php';
xoops_load('xoopsmediauploader');

$catHandler = $helper->getHandler('Category');
$photosHandler = $helper->getHandler('Photos');
$textHandler = $helper->getHandler('Text');

// GET variables
$caller = Request::getString('caller', '', 'GET');
// POST variables
$preview_name = Request::getString('preview_name', '', 'POST');
// check INSERTABLE
if (!($global_perms & GPERM_INSERTABLE)) {
    redirect_header(XOOPS_URL . '/user.php', 2, _ALBM_MUSTREGFIRST);
}

// check Categories exist
$count = $catHandler->getCount();
if ($count < 1) {
//    redirect_header(XOOPS_URL . "/modules/$moduleDirName/", 2, _ALBM_MUSTADDCATFIRST);
    $helper->redirect('index.php', 2, _ALBM_MUSTADDCATFIRST);
}

// check file_uploads = on
if (!ini_get('file_uploads')) {
    $file_uploads_off = true;
}

// get flag of safe_mode
//$safe_mode_flag = ini_get('safe_mode');

// check or make photos_dir
if (!is_dir($photos_dir)) {
    //    if ($safe_mode_flag) {
    //        $helper->redirect('index.php', 10, "At first create & chmod 777 '$photos_dir' by ftp or shell.");
    //    }

    $rs = mkdir($photos_dir);
    if (!$rs) {
        $helper->redirect('index.php', 10, "$photos_dir is not a directory");
    } else {
        @chmod($photos_dir, 0777);
    }
}

// check or make thumbs_dir
if ($myalbum_makethumb && !is_dir($thumbs_dir)) {
    //    if ($safe_mode_flag) {
    //        $helper->redirect('index.php', 10, "At first create & chmod 777 '$thumbs_dir' by ftp or shell.");
    //    }

    $rs = mkdir($thumbs_dir);
    if (!$rs) {
        $helper->redirect('index.php', 10, "$thumbs_dir is not a directory");
    } else {
        @chmod($thumbs_dir, 0777);
    }
}

// check or set permissions of photos_dir
if (!is_writable($photos_dir) || !is_readable($photos_dir)) {
    $rs = chmod($photos_dir, 0777);
    if (!$rs) {
        $helper->redirect('index.php', 5, "chmod 0777 into $photos_dir failed");
    }
}

// check or set permissions of thumbs_dir
if ($myalbum_makethumb && !is_writable($thumbs_dir)) {
    $rs = chmod($thumbs_dir, 0777);
    if (!$rs) {
        $helper->redirect('index.php', 5, "chmod 0777 into $thumbs_dir failed");
    }
}

if (!empty($_POST['submit'])) {
    // anti-CSRF
    $xsecurity = new \XoopsSecurity();
    if (!$xsecurity->checkReferer()) {
        exit('XOOPS_URL is not included in your REFERER');
    }

    $my_uid = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->uid() : 0;
    $submitter = $my_uid;
    $photo_obj = $photosHandler->create();
    $cid       = Request::getInt('cid', 0, 'POST');

    // Check if cid is valid
    if ($cid <= 0) {
        $helper->redirect('submit.php', 2, 'Category is not specified.');
    }

    // Check if upload file name specified
    $field = $_POST['xoops_upload_file'][0];
    if (empty($field) || '' == $field) {
        exit('UPLOAD error: file name not specified');
    }
    $field = $_POST['xoops_upload_file'][0];

    if ('' == $_FILES[$field]['name']) {
        // No photo uploaded

        if ('' === trim($_POST['title'])) {
            $_POST['title'] = 'no title';
        }

        if ('' != $preview_name && is_readable("$photos_dir/$preview_name")) {
            $tmp_name = $preview_name;
        } else {
            if (empty($myalbum_allownoimage)) {
                redirect_header('submit.php', 2, _ALBM_NOIMAGESPECIFIED);
            } else {
                @copy("$mod_path/assets/images/pixel_trans.gif", "$photos_dir/pixel_trans.gif");
                $tmp_name = 'pixel_trans.gif';
            }
        }
    } elseif ('' == $_FILES[$field]['tmp_name']) {
        // Fail to upload (wrong file name etc.)
        require_once XOOPS_ROOT_PATH . '/header.php';
        echo _ALBM_FILEERROR;
        require_once XOOPS_ROOT_PATH . '/footer.php';
        exit;
    } else {
        if (isset($GLOBALS['myalbumModuleConfig']['myalbum_canresize'])
            && $GLOBALS['myalbumModuleConfig']['myalbum_canresize']) {
            $uploader = new \XoopsMediaUploader(
                $GLOBALS['photos_dir'],
                explode('|', $helper->getConfig('myalbum_allowedmime')),
                $helper->getConfig('myalbum_fsize'),
                null,
                null);
        } else {
            $uploader = new \XoopsMediaUploader(
                $GLOBALS['photos_dir'],
                explode('|', $helper->getConfig('myalbum_allowedmime')),
                $helper->getConfig('myalbum_fsize'),
                $helper->getConfig('myalbum_width'),
                $helper->getConfig('myalbum_height')
            );
        }

        $uploader->setPrefix('tmp_');
        if ($uploader->fetchMedia($field) && $uploader->upload()) {
            // The original file name will be the title if title is empty
            if ('' === trim($_POST['title'])) {
                $_POST['title'] = $uploader->getMediaName();
            }

            $tmp_name = $uploader->getSavedFileName();
        } else {
            // Fail to upload (sizeover etc.)
            require_once XOOPS_ROOT_PATH . '/header.php';

            echo $uploader->getErrors();
            @unlink($uploader->getSavedDestination());

            require_once XOOPS_ROOT_PATH . '/footer.php';
            exit;
        }
    }

    if (!is_readable("$photos_dir/$tmp_name")) {
        redirect_header('submit.php', 2, _ALBM_FILEREADERROR);
    }

    $title     = Request::getString('title', '', 'POST');
    $desc_text = Request::getText('desc_text', '', 'POST');
    $date      = time();
    $fileparts = explode('.', $tmp_name);
    $ext       = $fileparts[count($fileparts) - 1];
    $status    = ($global_perms & GPERM_SUPERINSERT) ? 1 : 0;

    $photo_obj->setVar('cid', $cid);
    $photo_obj->setVar('title', $title);
    $photo_obj->setVar('ext', $ext);
    $photo_obj->setVar('submitter', $submitter);
    $photo_obj->setVar('status', $status);
    $photo_obj->setVar('date', $date);
    $photo_obj->setVar('hits', 0);
    $photo_obj->setVar('rating', 0);
    $photo_obj->setVar('votes', 0);
    $photo_obj->setVar('comments', 0);
    $photo_obj->setVar('tags', ($_POST['tags'] ?? ''));
    $newid     = $photosHandler->insert($photo_obj, true);
    $photo_obj = $photosHandler->get($newid);

    if ($helper->getConfig('tag')) {
        /** @var TagTagHandler $tagHandler */
        $tagHandler = Helper::getInstance()->getHandler('Tag'); // xoops_getModuleHandler('tag', 'tag');
        $tagHandler->updateByItem($_POST['tags'], $newid, $GLOBALS['myalbumModule']->getVar('dirname'), $cid);
    }

    Utility::editPhoto($GLOBALS['photos_dir'] . "/$tmp_name", $GLOBALS['photos_dir'] . "/$newid.$ext");
    $dim = getimagesize($GLOBALS['photos_dir'] . "/$newid.$ext");
    $photo_obj->setVar('res_x', $dim[0]);
    $photo_obj->setVar('res_y', $dim[1]);
    @$photosHandler->insert($photo_obj, true);

    if (!Utility::createThumb($GLOBALS['photos_dir'] . "/$newid.$ext", $newid, $ext)) {
        $xoopsDB->query("DELETE FROM $table_photos WHERE lid=$newid");
        redirect_header('submit.php', 2, _ALBM_FILEREADERROR);
    }

    $text = $textHandler->create();
    $text->setVar('lid', $photo_obj->getVar('lid'));
    $text->setVar('description', $desc_text);
    @$textHandler->insert($text, true);

    // Update User's Posts (Should be modified when need admission.)
    $xoopsDB->query('UPDATE ' . $xoopsDB->prefix('users') . " SET posts=posts+'$myalbum_addposts' WHERE uid='$submitter'");

    // Trigger Notification
    if ($status) {
        /** @var \XoopsNotificationHandler $notificationHandler */
        $notificationHandler = xoops_getHandler('notification');

        // Global Notification
        $notificationHandler->triggerEvent('global', 0, 'new_photo', ['PHOTO_TITLE' => $title, 'PHOTO_URI' => $photo_obj->getURL()]);

        // Category Notification
        $cat = $catHandler->get($cid);
        if (is_object($cat)) {
            $notificationHandler->triggerEvent(
                'category',
                $cid,
                'new_photo',
                [
                    'PHOTO_TITLE'    => $title,
                    'CATEGORY_TITLE' => $cat->getVar('title'),
                    'PHOTO_URI'      => $photo_obj->getURL(),
                ]
            );
        }
    }

    // Clear tempolary files
    Utility::clearTempFiles($photos_dir);

    $redirect_uri = XOOPS_URL . "/modules/$moduleDirName/viewcat.php?cid=$cid&amp;orderby=dateD";
    if ('imagemanager' === $caller) {
        $redirect_uri = XOOPS_URL . "/modules/$moduleDirName/close.php";
    }
    redirect_header($redirect_uri, 2, _ALBM_RECEIVED);
}

// Editing Display

if ('imagemanager' === $caller) {
    echo "<html><head>
        <link rel='stylesheet' type='text/css' media='all' href='" . XOOPS_URL . "/xoops.css'>
        <link rel='stylesheet' type='text/css' media='all' href='" . XOOPS_URL . "/modules/system/style.css'>
        <meta http-equiv='content-type' content='text/html; charset='" . _CHARSET . "'>
        <meta http-equiv='content-language' content='" . _LANGCODE . "'>
        </head><body>\n";
} else {
    require $GLOBALS['xoops']->path('header.php');
    Preview::header();
}

// Preview
if ('imagemanager' !== $caller && !empty($_POST['preview'])) {
    $photo['description'] = Request::getText('desc_text', '', 'POST');
    $photo['title']       = Request::getString('title', '', 'POST');
    $photo['cid']         = Request::getInt('cid', 0, 'POST');

    $field = $_POST['xoops_upload_file'][0];
    if (is_readable($_FILES[$field]['tmp_name'])) {
        // new preview
        if ($GLOBALS['myalbumModuleConfig']['myalbum_canresize']) {
            $uploader = new \XoopsMediaUploader(
                $GLOBALS['photos_dir'],
                explode('|', $helper->getConfig('myalbum_allowedmime')),
                $helper->getConfig('myalbum_fsize'),
                null,
                null
            );
        } else {
            $uploader = new \XoopsMediaUploader(
                $GLOBALS['photos_dir'],
                explode('|', $helper->getConfig('myalbum_allowedmime')),
                $helper->getConfig('myalbum_fsize'),
                $helper->getConfig('myalbum_width'),
                $helper->getConfig('myalbum_height')
            );
        }
        $uploader->setPrefix('tmp_');
        if ($uploader->fetchMedia($field) && $uploader->upload()) {
            $tmp_name     = $uploader->getSavedFileName();
            $preview_name = str_replace('tmp_', 'tmp_prev_', $tmp_name);
            Utility::editPhoto($GLOBALS['photos_dir'] . "/$tmp_name", $GLOBALS['photos_dir'] . "/$lid.$ext");
            [$imgsrc, $width_spec, $ahref] = Preview::getImageAttribsForPreview($preview_name);
        } else {
            @unlink($uploader->getSavedDestination());
            $imgsrc     = "$mod_url/assets/images/pixel_trans.gif";
            $width_spec = "width='$myalbum_thumbsize' height='$myalbum_thumbsize'";
            $ahref      = '';
        }
    } elseif ('' != $preview_name && is_readable("$photos_dir/$preview_name")) {
        // old preview
        [$imgsrc, $width_spec, $ahref] = Preview::getImageAttribsForPreview($preview_name);
    } else {
        // preview without image
        $imgsrc     = "$mod_url/assets/images/pixel_trans.gif";
        $width_spec = "width='$myalbum_thumbsize' height='$myalbum_thumbsize'";
        $ahref      = '';
    }

    // Display Preview
    $photo_for_tpl = [
        'description'    => $GLOBALS['myts']->displayTarea($photo['description'], 0, 1, 1, 1, 1, 1),
        'title'          => $GLOBALS['myts']->htmlSpecialChars($photo['title']),
        'width_spec'     => $width_spec,
        'submitter'      => $my_uid,
        'submitter_name' => Preview::getNameFromUid($my_uid),
        'imgsrc_thumb'   => $imgsrc,
        'ahref_photo'    => $ahref,
    ];
    $tpl           = new \XoopsTpl();
    require_once __DIR__ . '/include/assign_globals.php';
    $tpl->assign($myalbum_assign_globals);
    $tpl->assign('photo', $photo_for_tpl);
    echo "<table class='outer' style='width:100%;'>";
    $tpl->display("db:{$moduleDirName }_photo_in_list.tpl");
    echo "</table>\n";
} else {
    $photo = [
        'cid'         => Request::getInt('cid', 0, 'GET'),
        'description' => '',
        'title'       => '',
    ];
}

if ($helper->getConfig('htaccess')) {
    if (Request::hasVar('cid', 'GET') && isset($_GET['title'])) {
        $url = XOOPS_URL . '/' . $helper->getConfig('baseurl') . '/' . $_GET['title'] . '/submit,' . $_GET['cid'] . '.html';
    } else {
        $url = XOOPS_URL . '/' . $helper->getConfig('baseurl') . '/submit.html';
    }
    if (!mb_strpos($url, $_SERVER['REQUEST_URI'])) {
        header('HTTP/1.1 301 Moved Permanently');
        header('Location: ' . $url);
        exit(0);
    }
}
$GLOBALS['xoopsTpl']->assign('xoConfig', $GLOBALS['myalbumModuleConfig']);
$GLOBALS['xoopsTpl']->assign('mydirname', $GLOBALS['mydirname']);

echo Forms::getUserFormSubmit($caller, $photo, $lid);

if ('imagemanager' === $caller) {
    echo '</body></html>';
} else {
    Preview::footer();
    require $GLOBALS['xoops']->path('footer.php');
}
