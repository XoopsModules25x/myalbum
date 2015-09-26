<?php
include  __DIR__ . '/admin_header.php';
xoops_loadLanguage('admin');

xoops_cp_header();

//  myalbum_adminMenu(basename(__FILE__), 0);
$indexAdmin = new ModuleAdmin();

echo $indexAdmin->addNavigation('index.php');

$cat_handler      =& xoops_getmodulehandler('cat');
$comments_handler =& xoops_getmodulehandler('comments');
$photos_handler   =& xoops_getmodulehandler('photos');
$text_handler     =& xoops_getmodulehandler('text');
$votedata_handler =& xoops_getmodulehandler('votedata');
$group_handler    =& xoops_gethandler('group');

$netpbm_pipes = array(
    "jpegtopnm",
    "giftopnm",
    "pngtopnm",
    "pnmtojpeg",
    "pnmtopng",
    "ppmquant",
    "ppmtogif",
    "pnmscale",
    "pnmflip");

// PATH_SEPARATOR
if (!defined('PATH_SEPARATOR')) {
    if (DIRECTORY_SEPARATOR === '/') {
        define('PATH_SEPARATOR', ':');
    } else {
        define('PATH_SEPARATOR', ';');
    }
}

// Check the path to binaries of imaging packages
if (trim($myalbum_imagickpath) !== '' && substr($myalbum_imagickpath, -1) !== DIRECTORY_SEPARATOR) {
    $myalbum_imagickpath .= DIRECTORY_SEPARATOR;
}
if (trim($myalbum_netpbmpath) !== '' && substr($myalbum_netpbmpath, -1) !== DIRECTORY_SEPARATOR) {
    $myalbum_netpbmpath .= DIRECTORY_SEPARATOR;
}

// Environmental
$indexAdmin = new ModuleAdmin();
$title      = _AM_MB_PHPDIRECTIVE . '&nbsp;:&nbsp;' . _AM_H4_ENVIRONMENT;
$indexAdmin->addInfoBox($title);
// Safe Mode
$safe_mode_flag = ini_get("safe_mode");
$indexAdmin->addInfoBoxLine($title, "<label>'safe_mode' (" . _AM_MB_BOTHOK . "): %s</label>", (!$safe_mode_flag ? _AM_LABEL_OFF : _AM_LABEL_ON), (!$safe_mode_flag ? 'Red' : 'Green'));
// File Uploads
$rs = ini_get("file_uploads");
$indexAdmin->addInfoBoxLine($title, "<label>'file_uploads' (" . _AM_MB_NEEDON . "): %s</label>", (!$rs ? _AM_LABEL_OFF : _AM_LABEL_ON), (!$rs ? 'Red' : 'Green'));
// Register Globals
$rs = ini_get("register_globals");
$indexAdmin->addInfoBoxLine($title, "<label>'register_globals' (" . _AM_MB_BOTHOK . "): %s</label>", (!$rs ? _AM_LABEL_OFF : _AM_LABEL_ON), (!$rs ? 'Red' : 'Green'));
// File Uploads
$rs = ini_get("upload_max_filesize");
$indexAdmin->addInfoBoxLine($title, "<label>'upload_max_filesize': %s bytes</label>", $rs, (!$rs ? 'Red' : 'Green'));
// File Uploads
$rs = ini_get("post_max_size");
$indexAdmin->addInfoBoxLine($title, "<label>'post_max_size': %s bytes</label>", $rs, (!$rs ? 'Red' : 'Green'));
// File Uploads
$rs = ini_get("open_basedir");
$indexAdmin->addInfoBoxLine($title, "<label>'open_basedir': %s</label>", (!$rs ? _AM_LABEL_NOTHING : $rs), (!$rs ? 'Red' : 'Green'));
// File Uploads
$rs                   = ini_get("file_uploads");
$tmp_dirs             = explode(PATH_SEPARATOR, ini_get("upload_tmp_dir"));
$error_upload_tmp_dir = false;
foreach ($tmp_dirs as $dir) {
    if ($dir !== "" && (!is_writable($dir) || !is_readable($dir)) && $error_upload_tmp_dir === false) {
        $indexAdmin->addInfoBoxLine($title, "<label>'upload_tmp_dir': %s</label>", "Error: upload_tmp_dir ($dir) is not writable nor readable", 'Red');
        $error_upload_tmp_dir = true;
    }
}
if ($error_upload_tmp_dir === false) {
    $indexAdmin->addInfoBoxLine($title, "<label>'upload_tmp_dir': %s</label>", "ok - " . ini_get("upload_tmp_dir"), 'Green');
}

// Tables
$title = _AM_H4_TABLE;
$indexAdmin->addInfoBox($title);
$indexAdmin->addInfoBoxLine($title, "<label>" . _AM_MB_PHOTOSTABLE . ": " . $GLOBALS['table_photos'] . ": %s photos</label>", $photos_handler->getCount(new Criteria('`status`', '0', '>')), 'Purple');
$indexAdmin->addInfoBoxLine($title, "<label>" . _AM_MB_PHOTOSTABLE . ": " . $GLOBALS['table_photos'] . ": %s dead photos</label>", $photos_handler->getCountDeadPhotos(), 'Red');
$indexAdmin->addInfoBoxLine($title, "<label>" . _AM_MB_PHOTOSTABLE . ": " . $GLOBALS['table_photos'] . ": %s dead thumbs</label>", $photos_handler->getCountDeadThumbs(), 'Red');
$indexAdmin->addInfoBoxLine($title, "<label>" . _AM_MB_DESCRIPTIONTABLE . ": " . $GLOBALS['table_text'] . ": %s descriptions</label>", $text_handler->getCount(), 'Purple');
$indexAdmin->addInfoBoxLine($title, "<label>" . _AM_MB_DESCRIPTIONTABLE . ": " . $GLOBALS['table_text'] . ": %s bytes</label>", $text_handler->getBytes(), 'Orange');
$indexAdmin->addInfoBoxLine($title, "<label>" . _AM_MB_CATEGORIESTABLE . ": " . $GLOBALS['table_cat'] . ": %s categories</label>", $cat_handler->getCount(), 'Purple');
$indexAdmin->addInfoBoxLine($title, "<label>" . _AM_MB_VOTEDATATABLE . ": " . $GLOBALS['table_votedata'] . ": %s votes</label>", $votedata_handler->getCount(), 'Purple');
$indexAdmin->addInfoBoxLine($title, "<label>" . _AM_MB_COMMENTSTABLE . ": " . $GLOBALS['table_comments'] . ": %s comments</label>", $comments_handler->getCount(new Criteria('`com_modid`', $GLOBALS['myalbumModule']->getVar('mid'), '=')), 'Purple');

// Config
$title = _AM_H4_CONFIG;
$indexAdmin->addInfoBox($title);
if ($myalbum_imagingpipe == PIPEID_IMAGICK) {
    $indexAdmin->addInfoBoxLine($title, "<label>" . _AM_MB_PIPEFORIMAGES . ": ImageMagick : %s</label>", "Path: $myalbum_imagickpath", 'Brown');
    exec("{$myalbum_imagickpath}convert --help", $ret_array);
    if (count($ret_array) < 1) {
        $indexAdmin->addInfoBoxLine($title, "<label>" . _AM_MB_PIPEFORIMAGES . ": ImageMagick : %s</label>", "Error: {$myalbum_imagickpath}convert can't be executed", 'Red');
    } else {
        $indexAdmin->addInfoBoxLine($title, "<label>" . _AM_MB_PIPEFORIMAGES . ": ImageMagick : %s</label>", "{$ret_array[0]} &nbsp; Ok", 'Green');
    }
} elseif ($myalbum_imagingpipe == PIPEID_NETPBM) {
    $indexAdmin->addInfoBoxLine($title, "<label>" . _AM_MB_PIPEFORIMAGES . ": NetPBM : %s</label>", "Path: $myalbum_netpbmpath", 'Brown');
    foreach ($netpbm_pipes as $pipe) {
        $ret_array = array();
        exec("{$myalbum_netpbmpath}$pipe --version 2>&1", $ret_array);
        if (count($ret_array) < 1) {
            $indexAdmin->addInfoBoxLine($title, "<label>" . _AM_MB_PIPEFORIMAGES . ": NetPBM : %s</label>", "Error: {$myalbum_netpbmpath}{$pipe} can't be executed", 'Red');
        } else {
            $indexAdmin->addInfoBoxLine($title, "<label>" . _AM_MB_PIPEFORIMAGES . ": NetPBM : %s</label>", "{$pipe} : {$ret_array[0]} &nbsp; Ok", 'Green');
        }
    }
} else {
    if (function_exists('gd_info')) {
        $gd_info = gd_info();
        $indexAdmin->addInfoBoxLine($title, "<label>" . _AM_MB_PIPEFORIMAGES . ": GD : %s</label>", "GD Version: {$gd_info['GD Version']}", 'Brown');
    }
    if (function_exists('imagecreatetruecolor')) {
        if (imagecreatetruecolor(200, 200)) {
            $indexAdmin->addInfoBoxLine($title, "<label>" . _AM_MB_PIPEFORIMAGES . ": GD2 : %s</label>", _AM_MB_GD2SUCCESS, 'Green');
        } else {
            $indexAdmin->addInfoBoxLine($title, "<label>" . _AM_MB_PIPEFORIMAGES . ": GD2 : %s</label>", 'Failed', 'Red');
        }
    } else {
        $indexAdmin->addInfoBoxLine($title, "<label>" . _AM_MB_PIPEFORIMAGES . ": GD2 : %s</label>", 'Failed', 'Red');
    }
}

$title = _AM_H4_DIRECTORIES;
$indexAdmin->addInfoBox($title);

if (substr($myalbum_photospath, -1) === '/') {
    $indexAdmin->addInfoBoxLine($title, "<label>" . _AM_MB_DIRECTORYFORPHOTOS . ": " . XOOPS_ROOT_PATH . "$myalbum_photospath %s</label>", _AM_ERR_LASTCHAR, 'Red');
} elseif (ord($myalbum_photospath) != 0x2f) {
    $indexAdmin->addInfoBoxLine($title, "<label>" . _AM_MB_DIRECTORYFORPHOTOS . ": " . XOOPS_ROOT_PATH . "$myalbum_photospath %s</label>", _AM_ERR_FIRSTCHAR, 'Red');
} elseif (!is_dir($GLOBALS['photos_dir'])) {
    if ($safe_mode_flag) {
        $indexAdmin->addInfoBoxLine($title, "<label>" . _AM_MB_DIRECTORYFORPHOTOS . ": " . XOOPS_ROOT_PATH . "$myalbum_photospath %s</label>", _AM_ERR_PERMISSION, 'Red');
    } else {
        $rs = mkdir($GLOBALS['photos_dir'], 0777);
        if (!$rs) {
            $indexAdmin->addInfoBoxLine($title, "<label>" . _AM_MB_DIRECTORYFORPHOTOS . ": " . XOOPS_ROOT_PATH . "$myalbum_photospath %s</label>", _AM_ERR_NOTDIRECTORY, 'Red');
        } else {
            $indexAdmin->addInfoBoxLine($title, "<label>" . _AM_MB_DIRECTORYFORPHOTOS . ": " . XOOPS_ROOT_PATH . "$myalbum_photospath %s</label>", 'Ok', 'Green');
        }
    }
} elseif (!is_writable($GLOBALS['photos_dir']) || !is_readable($GLOBALS['photos_dir'])) {
    $indexAdmin->addInfoBoxLine($title, "<label>" . _AM_MB_DIRECTORYFORPHOTOS . ": " . XOOPS_ROOT_PATH . "$myalbum_photospath %s</label>", _AM_ERR_READORWRITE, 'Red');
} else {
    $indexAdmin->addInfoBoxLine($title, "<label>" . _AM_MB_DIRECTORYFORPHOTOS . ": " . XOOPS_ROOT_PATH . "$myalbum_photospath %s</label>", 'Ok', 'Green');
}

// thumbs
if ($myalbum_makethumb) {
    if (substr($myalbum_thumbspath, -1) === '/') {
        $indexAdmin->addInfoBoxLine($title, "<label>" . _AM_MB_DIRECTORYFORPHOTOS . ": " . XOOPS_ROOT_PATH . "$myalbum_thumbspath %s</label>", _AM_ERR_LASTCHAR, 'Red');
    } elseif (ord($myalbum_thumbspath) != 0x2f) {
        $indexAdmin->addInfoBoxLine($title, "<label>" . _AM_MB_DIRECTORYFORPHOTOS . ": " . XOOPS_ROOT_PATH . "$myalbum_thumbspath %s</label>", _AM_ERR_FIRSTCHAR, 'Red');
    } elseif (!is_dir($GLOBALS['thumbs_dir'])) {
        if ($safe_mode_flag) {
            $indexAdmin->addInfoBoxLine($title, "<label>" . _AM_MB_DIRECTORYFORPHOTOS . ": " . XOOPS_ROOT_PATH . "$myalbum_thumbspath %s</label>", _AM_ERR_PERMISSION, 'Red');
        } else {
            $rs = mkdir($GLOBALS['thumbs_dir'], 0777);
            if (!$rs) {
                $indexAdmin->addInfoBoxLine($title, "<label>" . _AM_MB_DIRECTORYFORPHOTOS . ": " . XOOPS_ROOT_PATH . "$myalbum_thumbspath %s</label>", _AM_ERR_NOTDIRECTORY, 'Red');
            } else {
                $indexAdmin->addInfoBoxLine($title, "<label>" . _AM_MB_DIRECTORYFORPHOTOS . ": " . XOOPS_ROOT_PATH . "$myalbum_thumbspath %s</label>", 'Ok', 'Green');
            }
        }
    } elseif (!is_writable($GLOBALS['thumbs_dir']) || !is_readable($GLOBALS['thumbs_dir'])) {
        $indexAdmin->addInfoBoxLine($title, "<label>" . _AM_MB_DIRECTORYFORPHOTOS . ": " . XOOPS_ROOT_PATH . "$myalbum_thumbspath %s</label>", _AM_ERR_READORWRITE, 'Red');
    } else {
        $indexAdmin->addInfoBoxLine($title, "<label>" . _AM_MB_DIRECTORYFORPHOTOS . ": " . XOOPS_ROOT_PATH . "$myalbum_thumbspath %s</label>", 'Ok', 'Green');
    }
}

echo $indexAdmin->renderIndex();
//  myalbum_footer_adminMenu();
include_once  __DIR__ . '/admin_footer.php';
