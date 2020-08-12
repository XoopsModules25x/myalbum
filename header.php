<?php
/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright    {@link https://xoops.org/ XOOPS Project}
 * @license      {@link https://www.gnu.org/licenses/gpl-2.0.html GNU GPL 2 or later}
 * @package
 * @since
 * @author       XOOPS Development Team
 */
require_once dirname(dirname(__DIR__)) . '/mainfile.php';
require XOOPS_ROOT_PATH . '/header.php';

$moduleDirName = basename(__DIR__);

$GLOBALS['mydirname'] = basename(__DIR__);
require_once XOOPS_ROOT_PATH . "/modules/$moduleDirName/include/read_configs.php";
require_once XOOPS_ROOT_PATH . "/modules/$moduleDirName/include/get_perms.php";
//require_once XOOPS_ROOT_PATH . "/modules/$moduleDirName/class/Utility.php";
//require_once XOOPS_ROOT_PATH . "/modules/$moduleDirName/class/preview.php";
//require_once XOOPS_ROOT_PATH . "/modules/$moduleDirName/class/myuploader.php";

$GLOBALS['myts'] = \MyTextSanitizer::getInstance();

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

/** @var \XoopsModules\Myalbum\Helper $helper */
$helper = \XoopsModules\Myalbum\Helper::getInstance();


/** @var MyalbumCatHandler $catHandler */
$catHandler         = $helper->getHandler('Category');
$cats               = $catHandler->getObjects(null, true);
$GLOBALS['cattree'] = new \XoopsObjectTree($cats, 'cid', 'pid', 0);

xoops_loadLanguage('main', $moduleDirName);

if ($GLOBALS['myalbumModuleConfig']['tag']) {
    require_once $GLOBALS['xoops']->path('modules/tag/include/formtag.php');
}

extract($GLOBALS['myalbumModuleConfig']);

if (!isset($GLOBALS['xoopsTpl']) || !is_object($GLOBALS['xoopsTpl'])) {
    require_once XOOPS_ROOT_PATH . '/class/template.php';
    $GLOBALS['xoopsTpl'] = new \XoopsTpl();
}

require_once XOOPS_ROOT_PATH . "/modules/$moduleDirName/include/assign_globals.php";
