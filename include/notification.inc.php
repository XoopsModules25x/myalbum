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

eval('function ' . $moduleDirName . ' . $moduleDirName . ');
        $module = $moduleHandler->getByDirname("' . $moduleDirName . '.$xoopsDB->prefix("\'' . $moduleDirName . ' . $moduleDirName . '";
        $rs = $xoopsDB->query( $sql ) ;
        list( $title ) = $xoopsDB->fetchRow( $rs ) ;
        $item[\'name\'] = $title ;
        $item[\'url\'] = "$mod_url/photo.php?lid=$item_id" ;
    }

    return $item;
}');
?>
