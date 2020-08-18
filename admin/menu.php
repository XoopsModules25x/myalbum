<?php

use XoopsModules\Myalbum\{
    Helper
};
use Xmf\Module\Admin;

include dirname(__DIR__) . '/preloads/autoloader.php';

$moduleDirName = basename(dirname(__DIR__));
$moduleDirNameUpper = mb_strtoupper($moduleDirName);

/** @var Helper $helper */
$helper = Helper::getInstance();
$helper->loadLanguage('common');
$helper->loadLanguage('feedback');

$pathIcon32 = Admin::menuIconPath('');
if (is_object($helper->getModule())) {
    $pathModIcon32 = $helper->getModule()->getInfo('modicons32');
}

$adminmenu[] = [
    'title' => _ALBM_MYALBUM_ADMENU_DASHBOARD,
    'icon'  => $pathIcon32 . '/home.png',
    'image' => $pathIcon32 . '/home.png',
    'link'  => 'admin/index.php',
];

$adminmenu[] = [
    'title' => _ALBM_MYALBUM_ADMENU2,
    'icon'  => $pathIcon32 . '/category.png',
    'image' => $pathModIcon32 . '/myalbum.index.png',
    'link'  => 'admin/main.php',
];

$adminmenu[] = [
    'title' => _ALBM_MYALBUM_ADMENU1,
    'icon'  => $pathIcon32 . '/photo.png',
    'image' => $pathModIcon32 . '/myalbum.photomanager.png',
    'link'  => 'admin/photomanager.php',
];

$adminmenu[] = [
    'title' => _ALBM_MYALBUM_ADMENU0,
    'icon'  => $pathIcon32 . '/button_ok.png',
    'image' => $pathModIcon32 . '/myalbum.admission.png',
    'link'  => 'admin/admission.php',
];

$adminmenu[] = [
    'title' => _ALBM_MYALBUM_ADMENU4,
    'icon'  => $pathIcon32 . '/exec.png',
    'image' => $pathModIcon32 . '/myalbum.batch.png',
    'link'  => 'admin/batch.php',
];

$adminmenu[] = [
    'title' => _ALBM_MYALBUM_ADMENU5,
    'icon'  => $pathIcon32 . '/update.png',
    'image' => $pathModIcon32 . '/myalbum.redothumbs.png',
    'link'  => 'admin/redothumbs.php',
];

$adminmenu[] = [
    'title' => _ALBM_MYALBUM_ADMENU_IMPORT,
    'icon'  => $pathIcon32 . '/download.png',
    'image' => $pathModIcon32 . '/myalbum.import.png',
    'link'  => 'admin/import.php',
];

$adminmenu[] = [
    'title' => _ALBM_MYALBUM_ADMENU_EXPORT,
    'icon'  => $pathIcon32 . '/export.png',
    'image' => $pathModIcon32 . '/myalbum.export.png',
    'link'  => 'admin/export.php',
];

$adminmenu[] = [
    'title' => _ALBM_MYALBUM_ADMENU_GPERM,
    'icon'  => $pathIcon32 . '/permissions.png',
    'image' => $pathModIcon32 . '/myalbum.permissions.png',
    'link'  => 'admin/groupperm_global.php',
];

// Blocks Admin
$adminmenu[] = [
    'title' => constant('CO_' . $moduleDirNameUpper . '_' . 'BLOCKS'),
    'link' => 'admin/blocksadmin.php',
    'icon' => $pathIcon32 . '/block.png',
];

//Feedback
$adminmenu[] = [
    'title' => constant('CO_' . $moduleDirNameUpper . '_' . 'ADMENU_FEEDBACK'),
    'link'  => 'admin/feedback.php',
    'icon'  => $pathIcon32 . '/mail_foward.png',
];

if (is_object($helper->getModule()) && $helper->getConfig('displayDeveloperTools')) {
    $adminmenu[] = [
        'title' => constant('CO_' . $moduleDirNameUpper . '_' . 'ADMENU_MIGRATE'),
        'link' => 'admin/migrate.php',
        'icon' => $pathIcon32 . '/database_go.png',
    ];
}

$adminmenu[] = [
    'title' => _ALBM_MYALBUM_ADMENU_ABOUT,
    'icon'  => $pathIcon32 . '/about.png',
    'image' => $pathIcon32 . '/about.png',
    'link'  => 'admin/about.php',
];
