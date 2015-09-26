<?php
$GLOBALS['mydirname']     = basename(dirname(__DIR__));
$module_handler           =& xoops_gethandler('module');
$GLOBALS['myalbumModule'] = $module_handler->getByDirname($GLOBALS['mydirname']);

defined("XOOPS_ROOT_PATH") or die("XOOPS root path not defined");

$path = dirname(dirname(dirname(__DIR__)));
include_once $path . '/mainfile.php';

$dirname         = basename(dirname(__DIR__));
$module_handler  =& xoops_gethandler('module');
$module          = $module_handler->getByDirname($dirname);
$pathIcon32      = $module->getInfo('icons32');
$pathModuleAdmin = $module->getInfo('dirmoduleadmin');
$pathLanguage    = $path . $pathModuleAdmin;

if (!file_exists($fileinc = $pathLanguage . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/' . 'main.php')) {
    $fileinc = $pathLanguage . '/language/english/main.php';
}

$adminmenu              = array();
$i                      = 0;
$adminmenu[$i]['title'] = _ALBM_MYALBUM_ADMENU_DASHBOARD;
$adminmenu[$i]['icon']  = $pathIcon32 . '/home.png';
$adminmenu[$i]['image'] = $pathIcon32 . '/home.png';
$adminmenu[$i]['link']  = "admin/index.php";
++$i;
$adminmenu[$i]['title'] = _ALBM_MYALBUM_ADMENU2;
$adminmenu[$i]['icon']  = $pathIcon32 . '/category.png';
$adminmenu[$i]['image'] = '/assets/images/icons/32/myalbum.index.png';
$adminmenu[$i]['link']  = "admin/main.php";
++$i;
$adminmenu[$i]['title'] = _ALBM_MYALBUM_ADMENU1;
$adminmenu[$i]['icon']  = $pathIcon32 . '/photo.png';
$adminmenu[$i]['image'] = '/assets/images/icons/32/myalbum.photomanager.png';
$adminmenu[$i]['link']  = "admin/photomanager.php";
++$i;
$adminmenu[$i]['title'] = _ALBM_MYALBUM_ADMENU0;
$adminmenu[$i]['icon']  = $pathIcon32 . '/button_ok.png';
$adminmenu[$i]['image'] = '/assets/images/icons/32/myalbum.admission.png';
$adminmenu[$i]['link']  = "admin/admission.php";
++$i;
$adminmenu[$i]['title'] = _ALBM_MYALBUM_ADMENU4;
$adminmenu[$i]['icon']  = $pathIcon32 . '/exec.png';
$adminmenu[$i]['image'] = '/assets/images/icons/32/myalbum.batch.png';
$adminmenu[$i]['link']  = "admin/batch.php";
++$i;
$adminmenu[$i]['title'] = _ALBM_MYALBUM_ADMENU5;
$adminmenu[$i]['icon']  = $pathIcon32 . '/update.png';
$adminmenu[$i]['image'] = '/assets/images/icons/32/myalbum.redothumbs.png';
$adminmenu[$i]['link']  = "admin/redothumbs.php";
++$i;
$adminmenu[$i]['title'] = _ALBM_MYALBUM_ADMENU_IMPORT;
$adminmenu[$i]['icon']  = $pathIcon32 . '/download.png';
$adminmenu[$i]['image'] = '/assets/images/icons/32/myalbum.import.png';
$adminmenu[$i]['link']  = "admin/import.php";
++$i;
$adminmenu[$i]['title'] = _ALBM_MYALBUM_ADMENU_EXPORT;
$adminmenu[$i]['icon']  = $pathIcon32 . '/export.png';
$adminmenu[$i]['image'] = '/assets/images/icons/32/myalbum.export.png';
$adminmenu[$i]['link']  = "admin/export.php";
++$i;
$adminmenu[$i]['title'] = _ALBM_MYALBUM_ADMENU_GPERM;
$adminmenu[$i]['icon']  = $pathIcon32 . '/permissions.png';
$adminmenu[$i]['image'] = '/assets/images/icons/32/myalbum.permissions.png';
$adminmenu[$i]['link']  = "admin/groupperm_global.php";
++$i;
$adminmenu[$i]['title'] = _ALBM_MYALBUM_ADMENU_ABOUT;
$adminmenu[$i]['icon']  = $pathIcon32 . '/about.png';
$adminmenu[$i]['image'] = $pathIcon32 . '/about.png';
$adminmenu[$i]['link']  = "admin/about.php";
