<?php
require_once dirname(dirname(dirname(__DIR__))) . '/mainfile.php';
require_once dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';
require_once dirname(__DIR__) . '/include/functions.php';
require_once dirname(__DIR__) . '/include/read_configs.php';

if (!defined('_CHARSET')) {
    define("_CHARSET", "UTF-8");
}
if (!defined('_CHARSET_ISO')) {
    define("_CHARSET_ISO", "ISO-8859-1");
}

$GLOBALS['myts'] = MyTextSanitizer::getInstance();

$module_handler                 =& xoops_gethandler('module');
$config_handler                 =& xoops_gethandler('config');
$GLOBALS['myalbumModule']       = $module_handler->getByDirname($GLOBALS['mydirname']);
$GLOBALS['myalbumModuleConfig'] = $config_handler->getConfigList($GLOBALS['myalbumModule']->getVar('mid'));
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

$cat_handler        =& xoops_getmodulehandler('cat');
$cats               = $cat_handler->getObjects(null, true);
$GLOBALS['cattree'] = new XoopsObjectTree($cats, 'cid', 'pid', 0);

include_once $GLOBALS['xoops']->path('/Frameworks/moduleclasses/moduleadmin/moduleadmin.php');

$GLOBALS['myalbumImageIcon']  = XOOPS_URL . '/' . $GLOBALS['myalbumModule']->getInfo('modicons16');
$GLOBALS['myalbumImageAdmin'] = XOOPS_URL . '/' . $GLOBALS['myalbumModule']->getInfo('modicons32');

if ($GLOBALS['xoopsUser']) {
    $moduleperm_handler =& xoops_gethandler('groupperm');
    if (!$moduleperm_handler->checkRight('module_admin', $GLOBALS['myalbumModule']->getVar('mid'), $GLOBALS['xoopsUser']->getGroups())) {
        redirect_header(XOOPS_URL, 1, _NOPERM);
        exit();
    }
} else {
    redirect_header(XOOPS_URL . "/user.php", 1, _NOPERM);
    exit();
}

xoops_loadLanguage('user');
xoops_loadLanguage('admin', $mydirname);
xoops_loadLanguage('main', $mydirname);

$pathIcon16      = '../../' . $xoopsModule->getInfo('sysicons16');
$pathIcon32      = '../../' . $xoopsModule->getInfo('sysicons32');
$pathModuleAdmin = $xoopsModule->getInfo('dirmoduleadmin');

if (!isset($GLOBALS['xoopsTpl']) || !is_object($GLOBALS['xoopsTpl'])) {
    include_once(XOOPS_ROOT_PATH . "/class/template.php");
    $GLOBALS['xoopsTpl'] = new XoopsTpl();
}

$GLOBALS['xoopsTpl']->assign('pathImageIcon', $GLOBALS['myalbumImageIcon']);
$GLOBALS['xoopsTpl']->assign('pathImageAdmin', $GLOBALS['myalbumImageAdmin']);

if (isset($_GET['lid'])) {
    $lid    = (int)($_GET['lid']);
    $result = $GLOBALS['xoopsDB']->query("SELECT submitter FROM $table_photos where lid=$lid", 0);
    list($submitter) = $GLOBALS['xoopsDB']->fetchRow($result);
} else {
    $submitter = $GLOBALS['xoopsUser']->getVar('uid');
}

if ($GLOBALS['myalbumModuleConfig']['tag']) {
    include_once $GLOBALS['xoops']->path('modules' . DS . 'tag' . DS . 'include' . DS . 'formtag.php');
}

extract($GLOBALS['myalbumModuleConfig']);
