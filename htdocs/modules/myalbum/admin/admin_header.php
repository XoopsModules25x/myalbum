<?php

$moduleDirName = basename(dirname(__DIR__));
require_once dirname(dirname(dirname(__DIR__))) . '/mainfile.php';
require_once dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';
require_once dirname(__DIR__) . '/class/utilities.php';
require_once dirname(__DIR__) . '/include/read_configs.php';

if (!defined('_CHARSET')) {
    define('_CHARSET', 'UTF-8');
}
if (!defined('_CHARSET_ISO')) {
    define('_CHARSET_ISO', 'ISO-8859-1');
}

$GLOBALS['myts'] = MyTextSanitizer::getInstance();

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

include_once $GLOBALS['xoops']->path('class' . DS . 'xoopsmailer.php');
include_once $GLOBALS['xoops']->path('class' . DS . 'tree.php');

$catHandler         = xoops_getModuleHandler('cat');
$cats               = $catHandler->getObjects(null, true);
$GLOBALS['cattree'] = new XoopsObjectTree($cats, 'cid', 'pid', 0);

$xoopsModuleAdminPath = $GLOBALS['xoops']->path('www/' . $GLOBALS['xoopsModule']->getInfo('dirmoduleadmin'));
require_once $xoopsModuleAdminPath . '/moduleadmin.php';

$GLOBALS['myalbumImageIcon']  = XOOPS_URL . '/' . $GLOBALS['myalbumModule']->getInfo('modicons16');
$GLOBALS['myalbumImageAdmin'] = XOOPS_URL . '/' . $GLOBALS['myalbumModule']->getInfo('modicons32');

if ($GLOBALS['xoopsUser']) {
    $modulepermHandler = xoops_getHandler('groupperm');
    if (!$modulepermHandler->checkRight('module_admin', $GLOBALS['myalbumModule']->getVar('mid'), $GLOBALS['xoopsUser']->getGroups())) {
        redirect_header(XOOPS_URL, 1, _NOPERM);
    }
} else {
    redirect_header(XOOPS_URL . '/user.php', 1, _NOPERM);
}

xoops_loadLanguage('user');
xoops_loadLanguage('admin', $moduleDirName);
xoops_loadLanguage('main', $moduleDirName);

$pathIcon16 = $GLOBALS['xoops']->url('www/' . $GLOBALS['xoopsModule']->getInfo('sysicons16'));
$pathIcon32 = $GLOBALS['xoops']->url('www/' . $GLOBALS['xoopsModule']->getInfo('sysicons32'));

if (!isset($GLOBALS['xoopsTpl']) || !is_object($GLOBALS['xoopsTpl'])) {
    include_once XOOPS_ROOT_PATH . '/class/template.php';
    $GLOBALS['xoopsTpl'] = new XoopsTpl();
}

$GLOBALS['xoopsTpl']->assign('pathImageIcon', $GLOBALS['myalbumImageIcon']);
$GLOBALS['xoopsTpl']->assign('pathImageAdmin', $GLOBALS['myalbumImageAdmin']);

if (isset($_GET['lid'])) {
    $lid    = (int)$_GET['lid'];
    $result = $GLOBALS['xoopsDB']->query("SELECT submitter FROM $table_photos where lid=$lid", 0);
    list($submitter) = $GLOBALS['xoopsDB']->fetchRow($result);
} else {
    $submitter = $GLOBALS['xoopsUser']->getVar('uid');
}

if ($GLOBALS['myalbumModuleConfig']['tag']) {
    include_once $GLOBALS['xoops']->path('modules' . DS . 'tag' . DS . 'include' . DS . 'formtag.php');
}

extract($GLOBALS['myalbumModuleConfig']);
