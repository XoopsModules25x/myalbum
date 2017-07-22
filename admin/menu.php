<?php
$GLOBALS['mydirname']     = basename(dirname(__DIR__));
$moduleHandler            = xoops_getHandler('module');
$GLOBALS['myalbumModule'] = $moduleHandler->getByDirname($GLOBALS['mydirname']);

// defined('XOOPS_ROOT_PATH') || exit('XOOPS root path not defined');

$path = dirname(dirname(dirname(__DIR__)));
include_once $path . '/mainfile.php';

$moduleDirName         = basename(dirname(__DIR__));
$moduleHandler   = xoops_getHandler('module');
$module          = $moduleHandler->getByDirname($moduleDirName);
$pathIcon32      = '../../' . $module->getInfo('sysicons32');
$modIcon32      = '../../' . $module->getInfo('modicons32');
$pathModuleAdmin = $module->getInfo('dirmoduleadmin');
$pathLanguage    = $path . $pathModuleAdmin;

if (!file_exists($fileinc = $pathLanguage . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/' . 'main.php')) {
    $fileinc = $pathLanguage . '/language/english/main.php';
}

$adminmenu[] = array(
    'title' => _ALBM_MYALBUM_ADMENU_DASHBOARD,
    'icon'  => $pathIcon32 . '/home.png',
    'image' => $pathIcon32 . '/home.png',
    'link'  => 'admin/index.php',
);

$adminmenu[] = array(
    'title' => _ALBM_MYALBUM_ADMENU2,
    'icon'  => $pathIcon32 . '/category.png',
    'image' => $modIcon32 . '/myalbum.index.png',
    'link'  => 'admin/main.php',
);

$adminmenu[] = array(
    'title' => _ALBM_MYALBUM_ADMENU1,
    'icon'  => $pathIcon32 . '/photo.png',
    'image' => $modIcon32 . '/myalbum.photomanager.png',
    'link'  => 'admin/photomanager.php',
);

$adminmenu[] = array(
    'title' => _ALBM_MYALBUM_ADMENU0,
    'icon'  => $pathIcon32 . '/button_ok.png',
    'image' => $modIcon32 . '/myalbum.admission.png',
    'link'  => 'admin/admission.php',
);

$adminmenu[] = array(
    'title' => _ALBM_MYALBUM_ADMENU4,
    'icon'  => $pathIcon32 . '/exec.png',
    'image' => $modIcon32 . '/myalbum.batch.png',
    'link'  => 'admin/batch.php',
);

$adminmenu[] = array(
    'title' => _ALBM_MYALBUM_ADMENU5,
    'icon'  => $pathIcon32 . '/update.png',
    'image' => $modIcon32 . '/myalbum.redothumbs.png',
    'link'  => 'admin/redothumbs.php',
);

$adminmenu[] = array(
    'title' => _ALBM_MYALBUM_ADMENU_IMPORT,
    'icon'  => $pathIcon32 . '/download.png',
    'image' => $modIcon32 . '/myalbum.import.png',
    'link'  => 'admin/import.php',
);

$adminmenu[] = array(
    'title' => _ALBM_MYALBUM_ADMENU_EXPORT,
    'icon'  => $pathIcon32 . '/export.png',
    'image' => $modIcon32 . '/myalbum.export.png',
    'link'  => 'admin/export.php',
);

$adminmenu[] = array(
    'title' => _ALBM_MYALBUM_ADMENU_GPERM,
    'icon'  => $pathIcon32 . '/permissions.png',
    'image' => $modIcon32 . '/myalbum.permissions.png',
    'link'  => 'admin/groupperm_global.php',
);

$adminmenu[] = array(
    'title' => _ALBM_MYALBUM_ADMENU_ABOUT,
    'icon'  => $pathIcon32 . '/about.png',
    'image'  => $pathIcon32 . '/about.png',
    'link'  => 'admin/about.php',
);
