<?php

use XoopsModules\Myalbum;

require_once __DIR__ . '/admin_header.php';
xoops_loadLanguage('admin');

xoops_cp_header();

//  myalbum_adminMenu(basename(__FILE__), 0);
$adminObject = \Xmf\Module\Admin::getInstance();

$adminObject->displayNavigation(basename(__FILE__));

/** @var MyalbumCatHandler $catHandler */
$catHandler = $helper->getHandler('Category');
/** @var MyalbumCommentsHandler $commentsHandler */
$commentsHandler = $helper->getHandler('Comments');
/** @var MyalbumPhotosHandler $photosHandler */
$photosHandler = $helper->getHandler('Photos');
/** @var MyalbumTextHandler $textHandler */
$textHandler = $helper->getHandler('Text');
/** @var VotedataHandler $votedataHandler */
$votedataHandler = $helper->getHandler('Votedata');
$groupHandler    = xoops_getHandler('group');

$netpbm_pipes = [
    'jpegtopnm',
    'giftopnm',
    'pngtopnm',
    'pnmtojpeg',
    'pnmtopng',
    'ppmquant',
    'ppmtogif',
    'pnmscale',
    'pnmflip',
];

// PATH_SEPARATOR
if (!defined('PATH_SEPARATOR')) {
    if (DIRECTORY_SEPARATOR === '/') {
        define('PATH_SEPARATOR', ':');
    } else {
        define('PATH_SEPARATOR', ';');
    }
}

// Check the path to binaries of imaging packages
if ('' !== trim($myalbum_imagickpath) && '/' !== mb_substr($myalbum_imagickpath, -1)) {
    $myalbum_imagickpath .= '/';
}
if ('' !== trim($myalbum_netpbmpath) && '/' !== mb_substr($myalbum_netpbmpath, -1)) {
    $myalbum_netpbmpath .= '/';
}

// Environmental
$adminObject = \Xmf\Module\Admin::getInstance();
$title       = _AM_MB_PHPDIRECTIVE . '&nbsp;:&nbsp;' . _AM_H4_ENVIRONMENT;
$adminObject->addInfoBox($title);
// Safe Mode
//$safe_mode_flag = ini_get('safe_mode');
//$adminObject->addInfoBoxLine(sprintf( "<label>'safe_mode' (" . _AM_MB_BOTHOK . '): %s</label>', (!$safe_mode_flag ? _AM_LABEL_OFF : _AM_LABEL_ON), (!$safe_mode_flag ? 'Red' : 'Green'));
// File Uploads
$rs = ini_get('file_uploads');
$adminObject->addInfoBoxLine(sprintf("<label>'file_uploads' (" . _AM_MB_NEEDON . '): %s</label>', (!$rs ? _AM_LABEL_OFF : _AM_LABEL_ON)), '', (!$rs ? 'Red' : 'Green'));
// Register Globals
//$rs = ini_get('register_globals');
//$adminObject->addInfoBoxLine(sprintf( "<label>'register_globals' (" . _AM_MB_BOTHOK . '): %s</label>', (!$rs ? _AM_LABEL_OFF : _AM_LABEL_ON)), '', (!$rs ? 'Red' : 'Green'));
// File Uploads
$rs = ini_get('upload_max_filesize');
$adminObject->addInfoBoxLine(sprintf("<label>'upload_max_filesize': %s bytes</label>", $rs), '', (!$rs ? 'Red' : 'Green'));
// File Uploads
$rs = ini_get('post_max_size');
$adminObject->addInfoBoxLine(sprintf("<label>'post_max_size': %s bytes</label>", $rs), '', (!$rs ? 'Red' : 'Green'));
// File Uploads
$rs = ini_get('open_basedir');
$adminObject->addInfoBoxLine(sprintf("<label>'open_basedir': %s</label>", (!$rs ? _AM_LABEL_NOTHING : $rs)), '', (!$rs ? 'Red' : 'Green'));
// File Uploads
$rs                   = ini_get('file_uploads');
$tmp_dirs             = explode(PATH_SEPARATOR, ini_get('upload_tmp_dir'));
$error_upload_tmp_dir = false;
foreach ($tmp_dirs as $dir) {
    if ('' !== $dir && (!is_writable($dir) || !is_readable($dir)) && false === $error_upload_tmp_dir) {
        $adminObject->addInfoBoxLine(sprintf("<label>'upload_tmp_dir': %s</label>", "Error: upload_tmp_dir ($dir) is not writable nor readable"), '', 'Red');
        $error_upload_tmp_dir = true;
    }
}
if (false === $error_upload_tmp_dir) {
    $adminObject->addInfoBoxLine(sprintf("<label>'upload_tmp_dir': %s</label>", 'ok - ' . ini_get('upload_tmp_dir')), '', 'Green');
}

// Tables
$title = _AM_H4_TABLE;
$adminObject->addInfoBox($title);
$adminObject->addInfoBoxLine(sprintf('<label>' . _AM_MB_PHOTOSTABLE . ': ' . $GLOBALS['table_photos'] . ': %s photos</label>', $photosHandler->getCount(new \Criteria('`status`', '0', '>'))), '', 'Purple');
$adminObject->addInfoBoxLine(sprintf('<label>' . _AM_MB_PHOTOSTABLE . ': ' . $GLOBALS['table_photos'] . ': %s dead photos</label>', $photosHandler->getCountDeadPhotos()), '', 'Red');
$adminObject->addInfoBoxLine(sprintf('<label>' . _AM_MB_PHOTOSTABLE . ': ' . $GLOBALS['table_photos'] . ': %s dead thumbs</label>', $photosHandler->getCountDeadThumbs()), '', 'Red');
$adminObject->addInfoBoxLine(sprintf('<label>' . _AM_MB_DESCRIPTIONTABLE . ': ' . $GLOBALS['table_text'] . ': %s descriptions</label>', $textHandler->getCount()), '', 'Purple');
$adminObject->addInfoBoxLine(sprintf('<label>' . _AM_MB_DESCRIPTIONTABLE . ': ' . $GLOBALS['table_text'] . ': %s bytes</label>', $textHandler->getBytes()), '', 'Orange');
$adminObject->addInfoBoxLine(sprintf('<label>' . _AM_MB_CATEGORIESTABLE . ': ' . $GLOBALS['table_cat'] . ': %s categories</label>', $catHandler->getCount()), '', 'Purple');
$adminObject->addInfoBoxLine(sprintf('<label>' . _AM_MB_VOTEDATATABLE . ': ' . $GLOBALS['table_votedata'] . ': %s votes</label>', $votedataHandler->getCount()), '', 'Purple');
$adminObject->addInfoBoxLine(sprintf('<label>' . _AM_MB_COMMENTSTABLE . ': ' . $GLOBALS['table_comments'] . ': %s comments</label>', $commentsHandler->getCount(new \Criteria('`com_modid`', $GLOBALS['myalbumModule']->getVar('mid'), '='))), '', 'Purple');

// Config
$title = _AM_H4_CONFIG;
$adminObject->addInfoBox($title);
if (PIPEID_IMAGICK == $myalbum_imagingpipe) {
    $adminObject->addInfoBoxLine(sprintf('<label>' . _AM_MB_PIPEFORIMAGES . ': ImageMagick : %s</label>', "Path: $myalbum_imagickpath"), '', 'Brown');
    exec("{$myalbum_imagickpath}convert --help", $ret_array);
    if (count($ret_array) < 1) {
        $adminObject->addInfoBoxLine(sprintf('<label>' . _AM_MB_PIPEFORIMAGES . ': ImageMagick : %s</label>', "Error: {$myalbum_imagickpath}convert can't be executed"), '', 'Red');
    } else {
        $adminObject->addInfoBoxLine(sprintf('<label>' . _AM_MB_PIPEFORIMAGES . ': ImageMagick : %s</label>', "{$ret_array[0]} &nbsp; Ok"), '', 'Green');
    }
} elseif (PIPEID_NETPBM == $myalbum_imagingpipe) {
    $adminObject->addInfoBoxLine(sprintf('<label>' . _AM_MB_PIPEFORIMAGES . ': NetPBM : %s</label>', "Path: $myalbum_netpbmpath"), '', 'Brown');
    foreach ($netpbm_pipes as $pipe) {
        $ret_array = [];
        exec("{$myalbum_netpbmpath}$pipe --version 2>&1", $ret_array);
        if (count($ret_array) < 1) {
            $adminObject->addInfoBoxLine(sprintf('<label>' . _AM_MB_PIPEFORIMAGES . ': NetPBM : %s</label>', "Error: {$myalbum_netpbmpath}{$pipe} can't be executed"), '', 'Red');
        } else {
            $adminObject->addInfoBoxLine(sprintf('<label>' . _AM_MB_PIPEFORIMAGES . ': NetPBM : %s</label>', "{$pipe} : {$ret_array[0]} &nbsp; Ok"), '', 'Green');
        }
    }
} else {
    if (function_exists('gd_info')) {
        $gd_info = gd_info();
        $adminObject->addInfoBoxLine(sprintf('<label>' . _AM_MB_PIPEFORIMAGES . ': GD : %s</label>', "GD Version: {$gd_info['GD Version']}"), '', 'Brown');
    }
    if (function_exists('imagecreatetruecolor')) {
        if (imagecreatetruecolor(200, 200)) {
            $adminObject->addInfoBoxLine(sprintf('<label>' . _AM_MB_PIPEFORIMAGES . ': GD2 : %s</label>', _AM_MB_GD2SUCCESS), '', 'Green');
        } else {
            $adminObject->addInfoBoxLine(sprintf('<label>' . _AM_MB_PIPEFORIMAGES . ': GD2 : %s</label>', 'Failed'), '', 'Red');
        }
    } else {
        $adminObject->addInfoBoxLine(sprintf('<label>' . _AM_MB_PIPEFORIMAGES . ': GD2 : %s</label>', 'Failed'), '', 'Red');
    }
}

$title = _AM_H4_DIRECTORIES;
$adminObject->addInfoBox($title);

if ('/' === mb_substr($myalbum_photospath, -1)) {
    $adminObject->addInfoBoxLine(sprintf('<label>' . _AM_MB_DIRECTORYFORPHOTOS . ': ' . XOOPS_ROOT_PATH . "$myalbum_photospath %s</label>", _AM_ERR_LASTCHAR), '', 'Red');
} elseif (0x2f != ord($myalbum_photospath)) {
    $adminObject->addInfoBoxLine(sprintf('<label>' . _AM_MB_DIRECTORYFORPHOTOS . ': ' . XOOPS_ROOT_PATH . "$myalbum_photospath %s</label>", _AM_ERR_FIRSTCHAR), '', 'Red');
} elseif (!is_dir($GLOBALS['photos_dir'])) {
    //    if ($safe_mode_flag) {
    //        $adminObject->addInfoBoxLine(sprintf( '<label>' . _AM_MB_DIRECTORYFORPHOTOS . ': ' . XOOPS_ROOT_PATH . "$myalbum_photospath %s</label>", _AM_ERR_PERMISSION), '', 'Red');
    //    } else {
    $rs = mkdir($GLOBALS['photos_dir']);
    if (!$rs) {
        $adminObject->addInfoBoxLine(sprintf('<label>' . _AM_MB_DIRECTORYFORPHOTOS . ': ' . XOOPS_ROOT_PATH . "$myalbum_photospath %s</label>", _AM_ERR_NOTDIRECTORY), '', 'Red');
    } else {
        $adminObject->addInfoBoxLine(sprintf('<label>' . _AM_MB_DIRECTORYFORPHOTOS . ': ' . XOOPS_ROOT_PATH . "$myalbum_photospath %s</label>", 'Ok'), '', 'Green');
    }
    //    }
} elseif (!is_writable($GLOBALS['photos_dir']) || !is_readable($GLOBALS['photos_dir'])) {
    $adminObject->addInfoBoxLine(sprintf('<label>' . _AM_MB_DIRECTORYFORPHOTOS . ': ' . XOOPS_ROOT_PATH . "$myalbum_photospath %s</label>", _AM_ERR_READORWRITE), '', 'Red');
} else {
    $adminObject->addInfoBoxLine(sprintf('<label>' . _AM_MB_DIRECTORYFORPHOTOS . ': ' . XOOPS_ROOT_PATH . "$myalbum_photospath %s</label>", 'Ok'), '', 'Green');
}

// thumbs
if ($myalbum_makethumb) {
    if ('/' === mb_substr($myalbum_thumbspath, -1)) {
        $adminObject->addInfoBoxLine(sprintf('<label>' . _AM_MB_DIRECTORYFORPHOTOS . ': ' . XOOPS_ROOT_PATH . "$myalbum_thumbspath %s</label>", _AM_ERR_LASTCHAR), '', 'Red');
    } elseif (0x2f != ord($myalbum_thumbspath)) {
        $adminObject->addInfoBoxLine(sprintf('<label>' . _AM_MB_DIRECTORYFORPHOTOS . ': ' . XOOPS_ROOT_PATH . "$myalbum_thumbspath %s</label>", _AM_ERR_FIRSTCHAR), '', 'Red');
    } elseif (!is_dir($GLOBALS['thumbs_dir'])) {
        //        if ($safe_mode_flag) {
        //            $adminObject->addInfoBoxLine(sprintf( '<label>' . _AM_MB_DIRECTORYFORPHOTOS . ': ' . XOOPS_ROOT_PATH . "$myalbum_thumbspath %s</label>", _AM_ERR_PERMISSION), '', 'Red');
        //        } else {
        $rs = mkdir($GLOBALS['thumbs_dir']);
        if (!$rs) {
            $adminObject->addInfoBoxLine(sprintf('<label>' . _AM_MB_DIRECTORYFORPHOTOS . ': ' . XOOPS_ROOT_PATH . "$myalbum_thumbspath %s</label>", _AM_ERR_NOTDIRECTORY), '', 'Red');
        } else {
            $adminObject->addInfoBoxLine(sprintf('<label>' . _AM_MB_DIRECTORYFORPHOTOS . ': ' . XOOPS_ROOT_PATH . "$myalbum_thumbspath %s</label>", 'Ok'), '', 'Green');
        }
        //        }
    } elseif (!is_writable($GLOBALS['thumbs_dir']) || !is_readable($GLOBALS['thumbs_dir'])) {
        $adminObject->addInfoBoxLine(sprintf('<label>' . _AM_MB_DIRECTORYFORPHOTOS . ': ' . XOOPS_ROOT_PATH . "$myalbum_thumbspath %s</label>", _AM_ERR_READORWRITE), '', 'Red');
    } else {
        $adminObject->addInfoBoxLine(sprintf('<label>' . _AM_MB_DIRECTORYFORPHOTOS . ': ' . XOOPS_ROOT_PATH . "$myalbum_thumbspath %s</label>", 'Ok'), '', 'Green');
    }
}

if (!class_exists('MyAlbumUtility')) {
    xoops_load('utility', $moduleDirName);
}

foreach (array_keys($GLOBALS['uploadFolders']) as $i) {
    Myalbum\Utility::createFolder($uploadFolders[$i]);
    $adminObject->addConfigBoxLine($uploadFolders[$i], 'folder');
    //    $adminObject->addConfigBoxLine(array($folder[$i], '777'), 'chmod');
}

$adminObject->displayIndex();
//  myalbum_footer_adminMenu();
require_once __DIR__ . '/admin_footer.php';
