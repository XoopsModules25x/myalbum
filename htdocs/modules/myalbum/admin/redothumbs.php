<?php
// ------------------------------------------------------------------------- //
//                      myAlbum-P - XOOPS photo album                        //
//                        <http://www.peak.ne.jp/>                           //
// ------------------------------------------------------------------------- //

include  __DIR__ . '/admin_header.php';

// get and check $_POST['size']
$start = isset($_POST['start']) ? (int)($_POST['start']) : 0;
$size  = isset($_POST['size']) ? (int)($_POST['size']) : 10;
if ($size <= 0 || $size > 10000) {
    $size = 10;
}

$forceredo = isset($_POST['forceredo']) ? (int)($_POST['forceredo']) : false;
$removerec = isset($_POST['removerec']) ? (int)($_POST['removerec']) : false;
$resize    = isset($_POST['resize']) ? (int)($_POST['resize']) : false;

// get flag of safe_mode
$safe_mode_flag = ini_get("safe_mode");

// even if makethumb is off, it is treated as makethumb on
if (!$myalbum_makethumb) {
    $myalbum_makethumb = 1;
    $thumbs_dir        = XOOPS_ROOT_PATH . $GLOBALS['myalbum_thumbspath'];
    $thumbs_url        = XOOPS_URL . $GLOBALS['myalbum_thumbspath'];
}

// check if the directories of thumbs and photos are same.
if ($GLOBALS['thumbs_dir'] == $GLOBALS['photos_dir']) {
    die("The directory for thumbnails is same as for photos.");
}

// check or make thumbs_dir
if ($myalbum_makethumb && !is_dir($thumbs_dir)) {
    if ($safe_mode_flag) {
        redirect_header(XOOPS_URL . "/modules/$mydirname/admin/", 10, "At first create & chmod 777 '$thumbs_dir' by ftp or shell.");
        exit;
    }

    $rs = mkdir($thumbs_dir, 0777);
    if (!$rs) {
        redirect_header(XOOPS_URL . "/modules/$mydirname/", 10, "$thumbs_dir is not a directory");
        exit;
    } else {
        @chmod($thumbs_dir, 0777);
    }
}

if (!empty($_POST['submit'])) {
    ob_start();

    $result = $xoopsDB->query("SELECT lid , ext , res_x , res_y FROM " . $GLOBALS['xoopsDB']->prefix($table_photos) . " ORDER BY lid LIMIT $start , $size") or die("DB Error");
    $record_counter = 0;
    while (list($lid, $ext, $w, $h) = $xoopsDB->fetchRow($result)) {
        ++$record_counter;
        echo ($record_counter + $start - 1) . ") ";
        printf(_AM_FMT_CHECKING, "$lid.$ext");

        // Check if the main image exists
        if (!is_readable("$photos_dir/$lid.$ext")) {
            echo _AM_MB_PHOTONOTEXISTS . " &nbsp; ";
            if ($removerec) {
                myalbum_delete_photos("lid='$lid'");
                echo _AM_MB_RECREMOVED . "<br />\n";
            } else {
                echo _AM_MB_SKIPPED . "<br />\n";
            }
            continue;
        }

        // Check if the file is normal image
        if (!in_array(strtolower($ext), $myalbum_normal_exts)) {
            if ($forceredo || !is_readable("$thumbs_dir/$lid.gif")) {
                myalbum_create_thumb("$photos_dir/$lid.$ext", $lid, $ext);
                echo _AM_MB_CREATEDTHUMBS . "<br />\n";
            } else {
                echo _AM_MB_SKIPPED . "<br />\n";
            }
            continue;
        }

        // Size of main photo
        list($true_w, $true_h) = getimagesize("$photos_dir/$lid.$ext");
        echo "{$true_w}x{$true_h} .. ";

        // Check and resize the main photo if necessary
        if ($resize && ($true_w > $myalbum_width || $true_h > $myalbum_height)) {
            $tmp_path = "$photos_dir/myalbum_tmp_photo";
            @unlink($tmp_path);
            rename("$photos_dir/$lid.$ext", $tmp_path);
            myalbum_modify_photo($tmp_path, "$photos_dir/$lid.$ext");
            @unlink($tmp_path);
            echo _AM_MB_PHOTORESIZED . " &nbsp; ";
            list($true_w, $true_h) = getimagesize("$photos_dir/$lid.$ext");
        }

        // Check and repair record of the photo if necessary
        if ($true_w != $w || $true_h != $h) {
            $xoopsDB->query("UPDATE " . $GLOBALS['xoopsDB']->prefix($table_photos) . " SET res_x=$true_w, res_y=$true_h WHERE lid=$lid") or die("DB error: UPDATE photo table.");
            echo _AM_MB_SIZEREPAIRED . " &nbsp; ";
        }

        // Create Thumbs
        if (is_readable("$thumbs_dir/$lid.$ext")) {
            list($thumbs_w, $thumbs_h) = getimagesize("$thumbs_dir/$lid.$ext");
            echo "{$thumbs_w}x{$thumbs_h} ... ";
            if ($forceredo) {
                $retcode = myalbum_create_thumb("$photos_dir/$lid.$ext", $lid, $ext);
            } else {
                $retcode = 3;
            }
        } else {
            if ($myalbum_makethumb) {
                $retcode = myalbum_create_thumb("$photos_dir/$lid.$ext", $lid, $ext);
            } else {
                $retcode = 3;
            }
        }

        switch ($retcode) {
            case 0:
                echo _AM_MB_FAILEDREADING . "<br />\n";
                break;
            case 1:
                echo _AM_MB_CREATEDTHUMBS . "<br />\n";
                break;
            case 2:
                echo _AM_MB_BIGTHUMBS . "<br />\n";
                break;
            case 3:
                echo _AM_MB_SKIPPED . "<br />\n";
                break;
        }
    }
    $result_str = ob_get_contents();
    ob_end_clean();

    $start += $size;
}

// Make form objects
$form = new XoopsThemeForm(_AM_FORM_RECORDMAINTENANCE, "batchupload", "redothumbs.php");
$form->setExtra("enctype='multipart/form-data'");

$start_text      = new XoopsFormText(_AM_TEXT_RECORDFORSTARTING, "start", 20, 20, $start);
$size_text       = new XoopsFormText(_AM_TEXT_NUMBERATATIME . "<br /><br /><span style='font-weight:normal'>" . _AM_LABEL_DESCNUMBERATATIME . "</span>", "size", 20, 20, $size);
$forceredo_radio = new XoopsFormRadioYN(_AM_RADIO_FORCEREDO, 'forceredo', $forceredo);
$removerec_radio = new XoopsFormRadioYN(_AM_RADIO_REMOVEREC, 'removerec', $removerec);
$resize_radio    = new XoopsFormRadioYN(_AM_RADIO_RESIZE . " ({$myalbum_width}x{$myalbum_height})", 'resize', $resize);

if (isset($record_counter) && $record_counter < $size) {
    $submit_button = new XoopsFormLabel("", _AM_MB_FINISHED . " &nbsp; <a href='redothumbs.php'>" . _AM_LINK_RESTART . "</a>");
} else {
    $submit_button = new XoopsFormButton("", "submit", _AM_SUBMIT_NEXT, "submit");
}

// Render forms
xoops_cp_header();
$indexAdmin = new ModuleAdmin();
echo $indexAdmin->addNavigation('redothumbs.php');
//myalbum_adminMenu(basename(__FILE__), 5);

// check $xoopsModule
if (!is_object($xoopsModule)) {
    redirect_header("$mod_url/", 1, _NOPERM);
}
echo "<h3 style='text-align:left;'>" . sprintf(_AM_H3_FMT_RECORDMAINTENANCE, $xoopsModule->name()) . "</h3>\n";

myalbum_opentable();
$form->addElement($start_text);
$form->addElement($size_text);
$form->addElement($forceredo_radio);
$form->addElement($removerec_radio);
$form->addElement($resize_radio);
$form->addElement($submit_button);
$form->display();
myalbum_closetable();

if (isset($result_str)) {
    echo "<br />\n";
    echo $result_str;
}

//  myalbum_footer_adminMenu();
include_once  __DIR__ . '/admin_footer.php';
