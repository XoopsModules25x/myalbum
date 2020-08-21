<?php

use Xmf\Module\Admin;
use XoopsModules\Myalbum\{
    Helper,
    Utility
};
/** @var Helper $helper */
/** @var Utility $utility */

include dirname(__DIR__) . '/preloads/autoloader.php';

$moduleDirName = basename(dirname(__DIR__));
$moduleDirNameUpper = mb_strtoupper($moduleDirName); //$capsDirName

/** @var \XoopsDatabase $db */
$db = \XoopsDatabaseFactory::getDatabaseConnection();
$debug = false;
$helper = Helper::getInstance($debug);
$utility = new Utility();

$helper->loadLanguage('common');

//handlers
//$categoryHandler     = new Myalbum\CategoryHandler($db);
//$downloadHandler     = new Myalbum\DownloadHandler($db);

$pathIcon16 = Admin::iconUrl('', 16);
$pathIcon32 = Admin::iconUrl('', 32);
if (is_object($helper->getModule())) {
    $pathModIcon16 = $helper->getModule()->getInfo('modicons16');
    $pathModIcon32 = $helper->getModule()->getInfo('modicons32');
}

define('_ALBM_DIRNAME', basename(dirname(__DIR__)));
define('_ALBM_URL', XOOPS_URL . '/modules/' . _ALBM_DIRNAME);
define('_ALBM_PATH', XOOPS_ROOT_PATH . '/modules/' . _ALBM_DIRNAME);
define('_ALBM_IMAGES_URL', _ALBM_URL . '/assets/images');
define('_ALBM_ADMIN_URL', _ALBM_URL . '/admin');
define('_ALBM_ADMIN_PATH', _ALBM_PATH . '/admin/index.php');
define('_ALBM_ROOT_PATH', $GLOBALS['xoops']->path('modules/' . _ALBM_DIRNAME));
define('_ALBM_AUTHOR_LOGOIMG', _ALBM_URL . '/assets/images/logo.png');
define('_ALBM_UPLOAD_URL', XOOPS_UPLOAD_URL . '/' . _ALBM_DIRNAME); // WITHOUT Trailing slash
define('_ALBM_UPLOAD_PATH', XOOPS_UPLOAD_PATH . '/' . _ALBM_DIRNAME); // WITHOUT Trailing slash

if (!defined($moduleDirNameUpper . '_CONSTANTS_DEFINED')) {
    define($moduleDirNameUpper . '_DIRNAME', basename(dirname(__DIR__)));
    define($moduleDirNameUpper . '_ROOT_PATH', XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/');
    define($moduleDirNameUpper . '_PATH', XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/');
    define($moduleDirNameUpper . '_URL', XOOPS_URL . '/modules/' . $moduleDirName . '/');
    define($moduleDirNameUpper . '_IMAGE_URL', constant($moduleDirNameUpper . '_URL') . '/assets/images/');
    define($moduleDirNameUpper . '_IMAGE_PATH', constant($moduleDirNameUpper . '_ROOT_PATH') . '/assets/images');
    define($moduleDirNameUpper . '_ADMIN_URL', constant($moduleDirNameUpper . '_URL') . '/admin/');
    define($moduleDirNameUpper . '_ADMIN_PATH', constant($moduleDirNameUpper . '_ROOT_PATH') . '/admin/');
    define($moduleDirNameUpper . '_ADMIN', constant($moduleDirNameUpper . '_URL') . '/admin/index.php');
    //    define($moduleDirNameUpper . '_AUTHOR_LOGOIMG', constant($moduleDirNameUpper . '_URL') . '/assets/images/logoModule.png');
    define($moduleDirNameUpper . '_UPLOAD_URL', XOOPS_UPLOAD_URL . '/' . $moduleDirName); // WITHOUT Trailing slash
    define($moduleDirNameUpper . '_UPLOAD_PATH', XOOPS_UPLOAD_PATH . '/' . $moduleDirName); // WITHOUT Trailing slash
    define($moduleDirNameUpper . '_AUTHOR_LOGOIMG', $pathIcon32 . '/xoopsmicrobutton.gif');
    define($moduleDirNameUpper . '_CONSTANTS_DEFINED', 1);
}

$icons = [
    'edit'    => "<img src='" . $pathIcon16 . "/edit.png'  alt=" . _EDIT . "' align='middle'>",
    'delete'  => "<img src='" . $pathIcon16 . "/delete.png' alt='" . _DELETE . "' align='middle'>",
    'clone'   => "<img src='" . $pathIcon16 . "/editcopy.png' alt='" . _CLONE . "' align='middle'>",
    'preview' => "<img src='" . $pathIcon16 . "/view.png' alt='" . _PREVIEW . "' align='middle'>",
    'print'   => "<img src='" . $pathIcon16 . "/printer.png' alt='" . _CLONE . "' align='middle'>",
    'pdf'     => "<img src='" . $pathIcon16 . "/pdf.png' alt='" . _CLONE . "' align='middle'>",
    'add'     => "<img src='" . $pathIcon16 . "/add.png' alt='" . _ADD . "' align='middle'>",
    '0'       => "<img src='" . $pathIcon16 . "/0.png' alt='" . 0 . "' align='middle'>",
    '1'       => "<img src='" . $pathIcon16 . "/1.png' alt='" . 1 . "' align='middle'>",
];

$debug = false;

// MyTextSanitizer object
$myts = \MyTextSanitizer::getInstance();

if (!isset($GLOBALS['xoopsTpl']) || !($GLOBALS['xoopsTpl'] instanceof \XoopsTpl)) {
    require_once $GLOBALS['xoops']->path('class/template.php');
    $GLOBALS['xoopsTpl'] = new \XoopsTpl();
}

$GLOBALS['xoopsTpl']->assign('mod_url', XOOPS_URL . '/modules/' . $moduleDirName);
// Local icons path
if (is_object($helper->getModule())) {
    $pathModIcon16 = $helper->getModule()->getInfo('modicons16');
    $pathModIcon32 = $helper->getModule()->getInfo('modicons32');

    $GLOBALS['xoopsTpl']->assign('pathModIcon16', XOOPS_URL . '/modules/' . $moduleDirName . '/' . $pathModIcon16);
    $GLOBALS['xoopsTpl']->assign('pathModIcon32', $pathModIcon32);
}
