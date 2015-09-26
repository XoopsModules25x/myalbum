<?php
// $Id: notification.inc.php,v 1.1 2003/04/01 23:40:27 w4z004 Exp $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //

if (!defined('XOOPS_ROOT_PATH')) {
    exit;
}

$mydirname = basename(dirname(__DIR__));
if (!preg_match('/^myalbum\d*$/', $mydirname)) {
    die("invalid dirname of myalbum: " . htmlspecialchars($mydirname));
}

eval('

function ' . $mydirname . '_notify_iteminfo($not_category, $item_id)
{
    global $xoopsModule, $xoopsModuleConfig, $xoopsConfig , $xoopsDB ;

    if (empty($xoopsModule) || $xoopsModule->getVar("dirname") != "' . $mydirname . '" ) {
        $module_handler =& xoops_gethandler("module");
        $module =& $module_handler->getByDirname("' . $mydirname . '");
        $config_handler =& xoops_gethandler("config");
        $config =& $config_handler->getConfigsByCat(0,$module->getVar("mid"));
    } else {
        $module =& $xoopsModule;
        $config =& $xoopsModuleConfig;
    }
    $mod_url = XOOPS_URL . "/modules/" . $module->getVar("dirname") ;

    if ($not_category=="global") {
        $item["name"] = "";
        $item["url"] = "";
    } elseif ($not_category == "category") {
        // Assume we have a valid cid
        $sql = "SELECT title FROM ".$xoopsDB->prefix("' . $mydirname . '_cat")." WHERE cid=\'$item_id\'";
        $rs = $xoopsDB->query( $sql ) ;
        list( $title ) = $xoopsDB->fetchRow( $rs ) ;
        $item["name"] = $title ;
        $item["url"] = "$mod_url/viewcat.php?cid=$item_id" ;
    } elseif ($not_category == "photo") {
        // Assume we have a valid event_id
        $sql = "SELECT title FROM ".$xoopsDB->prefix("' . $mydirname . '_photos")." WHERE lid=\'$item_id\'";
        $rs = $xoopsDB->query( $sql ) ;
        list( $title ) = $xoopsDB->fetchRow( $rs ) ;
        $item["name"] = $title ;
        $item["url"] = "$mod_url/photo.php?lid=$item_id" ;
    }

    return $item;
}

');
