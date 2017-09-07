<?php
// ------------------------------------------------------------------------- //
//                      myAlbum-P - XOOPS photo album                        //
//                        <http://www.peak.ne.jp>                           //
// ------------------------------------------------------------------------- //

require_once __DIR__ . '/admin_header.php';

// From myalbum*
if (!empty($_POST['myalbum_import']) && !empty($_POST['cid'])) {

    // anti-CSRF
    if (!xoopsSecurity::checkReferer()) {
        die('XOOPS_URL is not included in your REFERER');
    }

    // get src module
    $src_cid     = (int)$_POST['cid'];
    $src_dirname = empty($_POST['src_dirname']) ? '' : $_POST['src_dirname'];
    if ($moduleDirName === $src_dirname) {
        die('source dirname is same as dest dirname: ' . htmlspecialchars($src_dirname));
    }
    if (!preg_match('/^myalbum(\d*)$/', $src_dirname, $regs)) {
        die('invalid dirname of myalbum: ' . htmlspecialchars($src_dirname));
    }
    /** @var XoopsModuleHandler $moduleHandler */
    $moduleHandler = xoops_getHandler('module');
    $module        = $moduleHandler->getByDirname($src_dirname);
    if (!is_object($module)) {
        die('invalid module dirname:' . htmlspecialchars($src_dirname));
    }
    $src_mid = $module->getVar('mid');

    // authority check
    if (!$GLOBALS['xoopsUser']->isAdmin($src_mid)) {
        exit;
    }

    // read configs from xoops_config directly
    $rs = $GLOBALS['xoopsDB']->query('SELECT conf_name,conf_value FROM  ' . $GLOBALS['xoopsDB']->prefix('config') . " WHERE conf_modid='$src_mid'");
    while (false !== (list($key, $val) = $GLOBALS['xoopsDB']->fetchRow($rs))) {
        $src_configs[$key] = $val;
    }
    $src_photos_dir = XOOPS_ROOT_PATH . $src_configs['myalbum_photospath'];
    $src_thumbs_dir = XOOPS_ROOT_PATH . $src_configs['myalbum_thumbspath'];
    // src table names
    $src_table_photos   = $GLOBALS['xoopsDB']->prefix("{$src_dirname}_photos");
    $src_table_cat      = $GLOBALS['xoopsDB']->prefix("{$src_dirname}_cat");
    $src_table_text     = $GLOBALS['xoopsDB']->prefix("{$src_dirname}_text");
    $src_table_votedata = $GLOBALS['xoopsDB']->prefix("{$src_dirname}_votedata");

    if (isset($_POST['copyormove']) && $_POST['copyormove'] === 'move') {
        $move_mode = true;
    } else {
        $move_mode = false;
    }

    // create category
    $GLOBALS['xoopsDB']->query('INSERT INTO ' . $GLOBALS['xoopsDB']->prefix($table_cat) . "(pid, title, imgurl) SELECT '0',title,imgurl FROM $src_table_cat WHERE cid='$src_cid'")
    || die('DB error: INSERT cat table');
    $cid = $GLOBALS['xoopsDB']->getInsertId();

    // INSERT loop
    $rs           = $GLOBALS['xoopsDB']->query("SELECT lid,ext FROM $src_table_photos WHERE cid='$src_cid'");
    $import_count = 0;
    while (false !== (list($src_lid, $ext) = $GLOBALS['xoopsDB']->fetchRow($rs))) {

        // photos table
        $set_comments = $move_mode ? 'comments' : "'0'";
        $sql          = 'INSERT INTO ' . $GLOBALS['xoopsDB']->prefix($table_photos) . "(cid,title,ext,res_x,res_y,submitter,status,date,hits,rating,votes,comments) SELECT '$cid',title,ext,res_x,res_y,submitter,status,date,hits,rating,votes,$set_comments FROM $src_table_photos WHERE lid='$src_lid'";
        $GLOBALS['xoopsDB']->query($sql) || die('DB error: INSERT photo table');
        $lid = $GLOBALS['xoopsDB']->getInsertId();

        // text table
        $sql = 'INSERT INTO  ' . $GLOBALS['xoopsDB']->prefix($table_text) . " (lid,description) SELECT '$lid',description FROM $src_table_text WHERE lid='$src_lid'";
        $GLOBALS['xoopsDB']->query($sql);

        // votedata table
        $sql = 'INSERT INTO ' . $GLOBALS['xoopsDB']->prefix($table_votedata) . " (lid,ratinguser,rating,ratinghostname,ratingtimestamp) SELECT '$lid',ratinguser,rating,ratinghostname,ratingtimestamp FROM $src_table_votedata WHERE lid='$src_lid'";
        $GLOBALS['xoopsDB']->query($sql);

        @copy("$src_photos_dir/{$src_lid}.{$ext}", "$photos_dir/{$lid}.{$ext}");
        if (in_array(strtolower($ext), $myalbum_normal_exts)) {
            @copy("$src_thumbs_dir/{$src_lid}.{$ext}", "$thumbs_dir/{$lid}.{$ext}");
        } else {
            @copy("$src_photos_dir/{$src_lid}.gif", "$photos_dir/{$lid}.gif");
            @copy("$src_thumbs_dir/{$src_lid}.gif", "$thumbs_dir/{$lid}.gif");
        }

        // exec only moving mode
        if ($move_mode) {
            // moving comments
            $sql = 'UPDATE  ' . $GLOBALS['xoopsDB']->prefix('xoopscomments') . " SET com_modid='$myalbum_mid',com_itemid='$lid' WHERE com_modid='$src_mid' AND com_itemid='$src_lid'";
            $GLOBALS['xoopsDB']->query($sql);

            // delete source photos
            list($photos_dir, $thumbs_dir, $myalbum_mid, $table_photos, $table_text, $table_votedata, $saved_photos_dir, $saved_thumbs_dir, $saved_myalbum_mid, $saved_table_photos, $saved_table_text, $saved_table_votedata) = [
                $src_photos_dir,
                $src_thumbs_dir,
                $src_mid,
                $src_table_photos,
                $src_table_text,
                $src_table_votedata,
                $photos_dir,
                $thumbs_dir,
                $myalbum_mid,
                $GLOBALS['xoopsDB']->prefix($table_photos),
                $GLOBALS['xoopsDB']->prefix($table_text),
                $GLOBALS['xoopsDB']->prefix($table_votedata)
            ];
            MyalbumUtility::deletePhotos("lid='$src_lid'");
            list($photos_dir, $thumbs_dir, $myalbum_mid, $table_photos, $table_text, $table_votedata) = [
                $saved_photos_dir,
                $saved_thumbs_dir,
                $saved_myalbum_mid,
                $saved_table_photos,
                $saved_table_text,
                $saved_table_votedata
            ];
        }

        ++$import_count;
    }

    redirect_header('import.php', 2, sprintf(_AM_FMT_IMPORTSUCCESS, $import_count));
} // From imagemanager
else {
    if (!empty($_POST['imagemanager_import']) && !empty($_POST['imgcat_id'])) {

        // authority check
        $syspermHandler = xoops_getHandler('groupperm');
        if (!$syspermHandler->checkRight('system_admin', XOOPS_SYSTEM_IMAGE, $GLOBALS['xoopsUser']->getGroups())) {
            exit;
        }

        // anti-CSRF
        if (!xoopsSecurity::checkReferer()) {
            die('XOOPS_URL is not included in your REFERER');
        }

        // get src information
        $src_cid          = (int)$_POST['imgcat_id'];
        $src_table_photos = $GLOBALS['xoopsDB']->prefix('image');
        $src_table_cat    = $GLOBALS['xoopsDB']->prefix('imagecategory');

        // create category
        $crs = $GLOBALS['xoopsDB']->query("SELECT imgcat_name,imgcat_storetype FROM $src_table_cat WHERE imgcat_id='$src_cid'");
        list($imgcat_name, $imgcat_storetype) = $GLOBALS['xoopsDB']->fetchRow($crs);

        $GLOBALS['xoopsDB']->query('INSERT INTO ' . $GLOBALS['xoopsDB']->prefix($table_cat) . "SET pid=0,title='" . addslashes($imgcat_name) . "'")
        || exit('DB error: INSERT cat table');
        $cid = $GLOBALS['xoopsDB']->getInsertId();

        // INSERT loop
        $rs           = $GLOBALS['xoopsDB']->query("SELECT image_id,image_name,image_nicename,image_created,image_display FROM $src_table_photos WHERE imgcat_id='$src_cid'");
        $import_count = 0;
        while (false !== (list($image_id, $image_name, $image_nicename, $image_created, $image_display) = $GLOBALS['xoopsDB']->fetchRow($rs))) {
            $src_file = XOOPS_UPLOAD_PATH . "/$image_name";
            $ext      = substr(strrchr($image_name, '.'), 1);

            // photos table
            $sql = 'INSERT INTO  ' . $GLOBALS['xoopsDB']->prefix($table_photos) . "SET cid='$cid',title='" . addslashes($image_nicename) . "',ext='$ext',submitter='$my_uid',status='$image_display',date='$image_created'";
            $GLOBALS['xoopsDB']->query($sql) || die('DB error: INSERT photo table');
            $lid = $GLOBALS['xoopsDB']->getInsertId();

            // text table
            $sql = 'INSERT INTO  ' . $GLOBALS['xoopsDB']->prefix($table_text) . " SET lid='$lid',description=''";
            $GLOBALS['xoopsDB']->query($sql);

            $dst_file = "$photos_dir/{$lid}.{$ext}";
            if ($imgcat_storetype === 'db') {
                $fp = fopen($dst_file, 'wb');
                if ($fp === false) {
                    continue;
                }
                $brs = $GLOBALS['xoopsDB']->query('SELECT image_body FROM  ' . $GLOBALS['xoopsDB']->prefix('imagebody') . " WHERE image_id='$image_id'");
                list($body) = $GLOBALS['xoopsDB']->fetchRow($brs);
                fwrite($fp, $body);
                fclose($fp);
                MyalbumUtility::createThumb($dst_file, $lid, $ext);
            } else {
                @copy($src_file, $dst_file);
                MyalbumUtility::createThumb($src_file, $lid, $ext);
            }

            list($width, $height, $type) = getimagesize($dst_file);
            $GLOBALS['xoopsDB']->query('UPDATE ' . $GLOBALS['xoopsDB']->prefix($table_photos) . "SET res_x='$width',res_y='$height' WHERE lid='$lid'");

            ++$import_count;
        }

        redirect_header('import.php', 2, sprintf(_AM_FMT_IMPORTSUCCESS, $import_count));
    }
}

require_once __DIR__ . '/../include/myalbum.forms.php';
xoops_cp_header();
$adminObject = \Xmf\Module\Admin::getInstance();
$adminObject->displayNavigation(basename(__FILE__));
//myalbum_adminMenu(basename(__FILE__), 6);
$GLOBALS['xoopsTpl']->assign('admin_title', sprintf(_AM_H3_FMT_IMPORTTO, $xoopsModule->name()));
$GLOBALS['xoopsTpl']->assign('mydirname', $GLOBALS['mydirname']);
$GLOBALS['xoopsTpl']->assign('photos_url', $GLOBALS['photos_url']);
$GLOBALS['xoopsTpl']->assign('thumbs_url', $GLOBALS['thumbs_url']);
$GLOBALS['xoopsTpl']->assign('forma', MyalbumForms::getAdminFormImportMyalbum());
$GLOBALS['xoopsTpl']->assign('formb', MyalbumForms::getAdminFormImportImageManager());

$GLOBALS['xoopsTpl']->display('db:' . $GLOBALS['mydirname'] . '_cpanel_import.tpl');

//  myalbum_footer_adminMenu();
require_once __DIR__ . '/admin_footer.php';
