<?php

if (!defined('XOOPS_ROOT_PATH')) {
    exit;
}

$GLOBALS['mydirname'] = basename(dirname(__DIR__));
if (preg_match('/^myalbum(\d*)$/', $GLOBALS['mydirname'], $regs)) {
    $GLOBALS['myalbum_number'] = $regs[1];
} else {
    die('invalid dirname of myalbum: ' . htmlspecialchars($GLOBALS['mydirname']));
}

global $xoopsConfig, $xoopsDB, $xoopsUser;

// module information
$GLOBALS['mod_url']       = XOOPS_URL . "/modules/{$GLOBALS['mydirname']}";
$GLOBALS['mod_path']      = XOOPS_ROOT_PATH . "/modules/{$GLOBALS['mydirname']}";
$GLOBALS['mod_copyright'] = "<a href='http://xoops.org/'><strong>myAlbum-P 3.07</strong></a>";

// global langauge file
xoops_loadLanguage('myalbum_constants', $GLOBALS['mydirname']);

// read from xoops_config
// get my mid
$moduleHandler            =& xoops_getHandler('module');
$GLOBALS['myalbumModule'] = $moduleHandler->getByDirname($GLOBALS['mydirname']);
if (is_object($GLOBALS['myalbumModule'])) {
    $GLOBALS['myalbum_mid'] = $GLOBALS[$GLOBALS['mydirname'] . 'Module']->getVar('mid');
    $configHandler          =& xoops_getHandler('config');
    // read configs from xoops_config directly
    $GLOBALS['myalbumModuleConfig'] = $configHandler->getConfigList($GLOBALS['myalbum_mid']);
    extract($GLOBALS['myalbumModuleConfig']);
}

// User Informations
if (empty($GLOBALS['xoopsUser'])) {
    $my_uid  = 0;
    $isadmin = false;
} else {
    $my_uid  = $GLOBALS['xoopsUser']->uid();
    $isadmin = $GLOBALS['xoopsUser']->isAdmin($GLOBALS['myalbum_mid']);
}

// Value Check
if (isset($GLOBALS['myalbum_addposts'])) {
    $GLOBALS['myalbum_addposts'] = (int)$GLOBALS['myalbum_addposts'];
} else {
    $GLOBALS['myalbum_addposts'] = 0;
}
if ($GLOBALS['myalbum_addposts'] < 0) {
    $GLOBALS['myalbum_addposts'] = 0;
}

// Path to Main Photo & Thumbnail ;
if (isset($GLOBALS['myalbum_photospath'])) {
    if (ord($GLOBALS['myalbum_photospath']) != 0x2f) {
        $GLOBALS['myalbum_photospath'] = DS . $GLOBALS['myalbum_photospath'];
    }
} else {
    $GLOBALS['myalbum_photospath'] = '/uploads/photos/';
}
if (isset($GLOBALS['myalbum_thumbspath'])) {
    if (ord($GLOBALS['myalbum_thumbspath']) != 0x2f) {
        $GLOBALS['myalbum_thumbspath'] = DS . $GLOBALS['myalbum_thumbspath'];
    }
} else {
    $GLOBALS['myalbum_thumbspath'] = '/uploads/thumbs/';
}
$photos_dir = XOOPS_ROOT_PATH . $GLOBALS['myalbum_photospath'];
$photos_url = XOOPS_URL . $GLOBALS['myalbum_photospath'];
if (isset($GLOBALS['myalbum_makethumb'])) {
    $thumbs_dir = XOOPS_ROOT_PATH . $GLOBALS['myalbum_thumbspath'];
    $thumbs_url = XOOPS_URL . $GLOBALS['myalbum_thumbspath'];
} else {
    $thumbs_dir = $photos_dir;
    $thumbs_url = $photos_url;
}

// DB table name
$GLOBALS['table_photos']   = "{$GLOBALS['mydirname']}_photos";
$GLOBALS['table_cat']      = "{$GLOBALS['mydirname']}_cat";
$GLOBALS['table_text']     = "{$GLOBALS['mydirname']}_text";
$GLOBALS['table_votedata'] = "{$GLOBALS['mydirname']}_votedata";
$GLOBALS['table_comments'] = 'xoopscomments';

// Pipe environment check
if (isset($GLOBALS['myalbum_imagingpipe'])) {
    if ($GLOBALS['myalbum_imagingpipe'] || function_exists('imagerotate')) {
        $GLOBALS['myalbum_canrotate'] = true;
    } else {
        $GLOBALS['myalbum_canrotate'] = false;
    }
}
if (isset($GLOBALS['myalbum_imagingpipe'])) {
    if ($GLOBALS['myalbum_imagingpipe'] || $GLOBALS['myalbum_forcegd2']) {
        $GLOBALS['myalbum_canresize'] = true;
    } else {
        $GLOBALS['myalbum_canresize'] = false;
    }
}
// Normal Extensions of Image
$GLOBALS['myalbum_normal_exts'] = array('jpg', 'jpeg', 'gif', 'png');

// Allowed extensions & MIME types
if (empty($GLOBALS['myalbum_allowedexts'])) {
    $GLOBALS['array_allowed_exts'] = $GLOBALS['myalbum_normal_exts'];
} else {
    $GLOBALS['array_allowed_exts'] = explode('|', $GLOBALS['myalbum_allowedexts']);
}
if (empty($GLOBALS['myalbum_allowedmime'])) {
    $GLOBALS['array_allowed_mimetypes'] = array();
} else {
    $GLOBALS['array_allowed_mimetypes'] = explode('|', $GLOBALS['myalbum_allowedmime']);
}
