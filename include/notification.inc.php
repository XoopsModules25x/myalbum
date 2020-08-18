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



$moduleDirName = basename(dirname(__DIR__));
if (!preg_match('/^Myalbum\d*$/', $moduleDirName)) {
    exit('invalid dirname of myalbum: ' . htmlspecialchars($moduleDirName, ENT_QUOTES | ENT_HTML5));
}

eval('function ' . $moduleDirName . '_notify_iteminfo($not_category, $item_id){
    global $xoopsModule, $xoopsModuleConfig, $xoopsConfig , $xoopsDB ;

    if (empty($xoopsModule) || $xoopsModule->getVar("dirname") != "' . $moduleDirName . '" ) {
        $moduleHandler = xoops_getHandler("module");
        $module = $moduleHandler->getByDirname("' . $moduleDirName . '");
        $configHandler = xoops_getHandler("config");
        $config = $configHandler->getConfigsByCat(0,$module->getVar("mid"));
    } else {
        $module =& $xoopsModule;
        $config = $xoopsModuleConfig;
    }
    $mod_url = XOOPS_URL . "/modules/" . $module->getVar("dirname") ;

    if ($not_category=="global") {
        $item["name"] = "";
        $item["url"] = "";
    } elseif ($not_category == "category") {
        // Assume we have a valid cid
        $sql = "SELECT title FROM ".$xoopsDB->prefix("' . $moduleDirName . '";
        $rs = $xoopsDB->query( $sql ) ;
        list( $title ) = $xoopsDB->fetchRow( $rs ) ;
        $item[\'name\'] = $title ;
        $item[\'url\'] = "$mod_url/viewcat.php?cid=$item_id" ;
    } elseif ($not_category == \'photo\') {
        // Assume we have a valid event_id
        $sql = \'SELECT title FROM \'.$xoopsDB->prefix(\'' . $moduleDirName . '";
        $rs = $xoopsDB->query( $sql ) ;
        list( $title ) = $xoopsDB->fetchRow( $rs ) ;
        $item[\'name\'] = $title ;
        $item[\'url\'] = "$mod_url/photo.php?lid=$item_id" ;
    }

    return $item;
}');
?>
