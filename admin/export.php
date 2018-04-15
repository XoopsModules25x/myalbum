<?php
// ------------------------------------------------------------------------- //
//                      myAlbum-P - XOOPS photo album                        //
//                        <http://www.peak.ne.jp>                           //
// ------------------------------------------------------------------------- //
require_once __DIR__ . '/admin_header.php';
require_once XOOPS_ROOT_PATH . '/modules/system/constants.php';

// To imagemanager
if (!empty($_POST['imagemanager_export']) && !empty($_POST['imgcat_id']) && !empty($_POST['cid'])) {

    // authority check
    $syspermHandler = xoops_getHandler('groupperm');
    if (!$syspermHandler->checkRight('system_admin', XOOPS_SYSTEM_IMAGE, $xoopsUser->getGroups())) {
    }

    // anti-CSRF
    if (!XoopsSecurity::checkReferer()) {
        die('XOOPS_URL is not included in your REFERER');
    }

    // get dst information
    $dst_cid          = \Xmf\Request::getInt('imgcat_id', 0, 'POST');
    $dst_table_photos = $xoopsDB->prefix('image');
    $dst_table_cat    = $xoopsDB->prefix('imagecategory');

    // get src information
    $src_cid          = \Xmf\Request::getInt('cid', 0, 'POST');
    $src_table_photos = $xoopsDB->prefix($table_photos);
    $src_table_cat    = $xoopsDB->prefix($table_cat);

    // get storetype of the imgcat
    $crs = $xoopsDB->query("SELECT imgcat_storetype,imgcat_maxsize FROM $dst_table_cat WHERE imgcat_id='$dst_cid'")
           || die('Invalid imgcat_id.');
    list($imgcat_storetype, $imgcat_maxsize) = $xoopsDB->fetchRow($crs);

    // mime type look up
    $mime_types = ['gif' => 'image/gif', 'png' => 'image/png', 'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg'];

    // INSERT loop
    $srs          = $xoopsDB->query("SELECT lid,ext,title,date,status FROM $src_table_photos WHERE cid='$src_cid'");
    $export_count = 0;
    while (false !== (list($lid, $ext, $title, $date, $status) = $xoopsDB->fetchRow($srs))) {
        $dst_node = uniqid('img', true);
        $dst_file = XOOPS_UPLOAD_PATH . "/{$dst_node}.{$ext}";
        $src_file = empty($_POST['use_thumb']) ? "$photos_dir/{$lid}.{$ext}" : "$thumbs_dir/{$lid}.{$ext}";

        if ('db' === $imgcat_storetype) {
            $fp = fopen($src_file, 'rb');
            if (false === $fp) {
                continue;
            }
            $body = addslashes(fread($fp, filesize($src_file)));
            fclose($fp);
        } else {
            if (!copy($src_file, $dst_file)) {
                continue;
            }
            $body = '';
        }

        // insert into image table
        $image_display = $status ? 1 : 0;
        $xoopsDB->query("INSERT INTO $dst_table_photos SET image_name='{$dst_node}.{$ext}',image_nicename='" . addslashes($title) . "',image_created='$date',image_mimetype='{$mime_types[$ext]}',image_display='$image_display',imgcat_id='$dst_cid'")
        || die('DB error: INSERT image table');
        if ($body) {
            $image_id = $xoopsDB->getInsertId();
            $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('imagebody') . " SET image_id='$image_id',image_body='$body'");
        }

        ++$export_count;
    }

    redirect_header('export.php', 2, sprintf(_AM_FMT_EXPORTSUCCESS, $export_count));
}

//
// Form Part
//

$syspermHandler = xoops_getHandler('groupperm');
if ($syspermHandler->checkRight('system_admin', XOOPS_SYSTEM_IMAGE, $xoopsUser->getGroups())) {
    xoops_cp_header();
    $adminObject = \Xmf\Module\Admin::getInstance();
    $adminObject->displayNavigation(basename(__FILE__));
    //  myalbum_adminMenu(basename(__FILE__), 7);
    $GLOBALS['xoopsTpl']->assign('admin_title', sprintf(_AM_H3_FMT_EXPORTTO, $GLOBALS['myalbumModule']->name()));
    $GLOBALS['xoopsTpl']->assign('mydirname', $GLOBALS['mydirname']);
    $GLOBALS['xoopsTpl']->assign('photos_url', $GLOBALS['photos_url']);
    $GLOBALS['xoopsTpl']->assign('thumbs_url', $GLOBALS['thumbs_url']);
    $GLOBALS['xoopsTpl']->assign('form', Myalbum\Forms::getAdminFormExport());

    $GLOBALS['xoopsTpl']->display('db:' . $GLOBALS['mydirname'] . '_cpanel_export.tpl');

    // check $GLOBALS['myalbumModule']
    //  myalbum_footer_adminMenu();
    require_once __DIR__ . '/admin_footer.php';
} else {
    redirect_header('dashboard.php', 5, _NOPERM);
}
