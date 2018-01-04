<?php

use XoopsModules\Myalbum;

require_once __DIR__ . '/../class/Helper.php';
//require_once __DIR__ . '/../include/common.php';
$helper = Myalbum\Helper::getInstance();

$pathIcon32 = \Xmf\Module\Admin::menuIconPath('');
$pathModIcon32 = $helper->getModule()->getInfo('modicons32');

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

$adminmenu[] = [
    'title' => _ALBM_MYALBUM_ADMENU_ABOUT,
    'icon'  => $pathIcon32 . '/about.png',
    'image'  => $pathIcon32 . '/about.png',
    'link'  => 'admin/about.php',
];
