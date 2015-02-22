<?php
if (!defined('XOOPS_ROOT_PATH')) {
    exit;
}

$global_perms = 0;
if (is_object($GLOBALS['xoopsDB'])) {
    if (!is_object($GLOBALS['xoopsUser'])) {
        $whr_groupid = "gperm_groupid=" . XOOPS_GROUP_ANONYMOUS;
    } else {
        $groups =& $GLOBALS['xoopsUser']->getGroups();
        $whr_groupid = "gperm_groupid IN (";
        foreach ($groups as $groupid) {
            $whr_groupid .= "$groupid,";
        }
        $whr_groupid = substr($whr_groupid, 0, -1) . ")";
    }
    if (isset($GLOBALS['myalbum_mid'])) {
        $GLOBALS['global_perms'] = array();
        $rs                      = $GLOBALS['xoopsDB']->query(
            "SELECT gperm_itemid FROM " . $GLOBALS['xoopsDB']->prefix("group_permission") . " WHERE gperm_modid='" . $GLOBALS['myalbum_mid'] . "' AND gperm_name='myalbum_global' AND ($whr_groupid)"
        );
        while (list($itemid) = $GLOBALS['xoopsDB']->fetchRow($rs)) {
            $GLOBALS['global_perms'] |= $itemid;
        }
    }
}
