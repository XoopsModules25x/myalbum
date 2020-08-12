<?php

use Xmf\Module\Admin;
use XoopsModules\Myalbum\{
    Helper
};

require dirname(__DIR__) . '/preloads/autoloader.php';

$moduleDirName = basename(dirname(__DIR__));
require_once dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';
require_once dirname(__DIR__) . '/include/functions.php';
require_once dirname(__DIR__) . '/include/read_configs.php';

if (!defined('_CHARSET')) {
    define('_CHARSET', 'UTF-8');
}
if (!defined('_CHARSET_ISO')) {
    define('_CHARSET_ISO', 'ISO-8859-1');
}

$GLOBALS['myts'] = \MyTextSanitizer::getInstance();

/** @var Helper $helper */
$helper = Helper::getInstance();

/** @var Admin $adminObject */
$adminObject = Admin::getInstance();

/** @var \XoopsModuleHandler $moduleHandler */
$moduleHandler                  = xoops_getHandler('module');
$configHandler                  = xoops_getHandler('config');
$GLOBALS['myalbumModule']       = $moduleHandler->getByDirname($GLOBALS['mydirname']);
$GLOBALS['myalbumModuleConfig'] = $configHandler->getConfigList($GLOBALS['myalbumModule']->getVar('mid'));
$GLOBALS['myalbum_mid']         = $GLOBALS['myalbumModule']->getVar('mid');
$GLOBALS['photos_dir']          = XOOPS_ROOT_PATH . $GLOBALS['myalbumModuleConfig']['myalbum_photospath'];
$GLOBALS['thumbs_dir']          = XOOPS_ROOT_PATH . $GLOBALS['myalbumModuleConfig']['myalbum_thumbspath'];
$GLOBALS['photos_url']          = XOOPS_URL . $GLOBALS['myalbumModuleConfig']['myalbum_photospath'];
$GLOBALS['thumbs_url']          = XOOPS_URL . $GLOBALS['myalbumModuleConfig']['myalbum_thumbspath'];

xoops_load('pagenav');
xoops_load('xoopslists');
xoops_load('xoopsformloader');

require_once $GLOBALS['xoops']->path('class/xoopsmailer.php');
require_once $GLOBALS['xoops']->path('class/tree.php');

/** @var Myalbum\CatHandler $catHandler */
$catHandler         = $helper->getHandler('Category');
$cats               = $catHandler->getObjects(null, true);
$GLOBALS['cattree'] = new \XoopsObjectTree($cats, 'cid', 'pid', 0);

//$xoopsModuleAdminPath = $GLOBALS['xoops']->path('www/' . $GLOBALS['xoopsModule']->getInfo('dirmoduleadmin'));
//require_once $xoopsModuleAdminPath . '/moduleadmin.php';

$GLOBALS['myalbumImageIcon']  = XOOPS_URL . '/' . $GLOBALS['myalbumModule']->getInfo('modicons16');
$GLOBALS['myalbumImageAdmin'] = XOOPS_URL . '/' . $GLOBALS['myalbumModule']->getInfo('modicons32');

if ($GLOBALS['xoopsUser']) {
    $grouppermHandler = xoops_getHandler('groupperm');
    if (!$grouppermHandler->checkRight('module_admin', $GLOBALS['myalbumModule']->getVar('mid'), $GLOBALS['xoopsUser']->getGroups())) {
        redirect_header(XOOPS_URL, 1, _NOPERM);
    }
} else {
    redirect_header(XOOPS_URL . '/user.php', 1, _NOPERM);
}

xoops_loadLanguage('user');
$helper->loadLanguage('admin');
$helper->loadLanguage('modinfo');
$helper->loadLanguage('main');

$pathIcon16 = $GLOBALS['xoops']->url('www/' . $GLOBALS['xoopsModule']->getInfo('sysicons16'));
$pathIcon32 = $GLOBALS['xoops']->url('www/' . $GLOBALS['xoopsModule']->getInfo('sysicons32'));

if (!isset($GLOBALS['xoopsTpl']) || !is_object($GLOBALS['xoopsTpl'])) {
    require_once XOOPS_ROOT_PATH . '/class/template.php';
    $GLOBALS['xoopsTpl'] = new \XoopsTpl();
}

$GLOBALS['xoopsTpl']->assign('pathImageIcon', $GLOBALS['myalbumImageIcon']);
$GLOBALS['xoopsTpl']->assign('pathImageAdmin', $GLOBALS['myalbumImageAdmin']);

if (\Xmf\Request::hasVar('lid', 'GET')) {
    $lid    = \Xmf\Request::getInt('lid', 0, 'GET');
    $result = $GLOBALS['xoopsDB']->query("SELECT submitter FROM $table_photos where lid=$lid", 0);
    list($submitter) = $GLOBALS['xoopsDB']->fetchRow($result);
} else {
    $submitter = $GLOBALS['xoopsUser']->getVar('uid');
}

if ($GLOBALS['myalbumModuleConfig']['tag']) {
    require_once $GLOBALS['xoops']->path('modules/tag/include/formtag.php');
}

extract($GLOBALS['myalbumModuleConfig']);
