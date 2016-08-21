<?php
if (!defined('XOOPS_ROOT_PATH')) {
    exit();
}

include dirname(__DIR__) . '/include/read_configs.php';

/**
 * Class for Blue Room Xcenter
 *
 * @author    Simon Roberts <simon@xoops.org>
 * @copyright copyright (c) 2009-2003 XOOPS.org
 * @package   kernel
 */
class MyalbumCat extends XoopsObject
{

    function __construct($id = null)
    {
        $this->initVar('cid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('pid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('title', XOBJ_DTYPE_TXTBOX, null, false, 50);
        $this->initVar('imgurl', XOBJ_DTYPE_TXTBOX, null, false, 150);
    }

    function toArray()
    {
        $ret = parent::toArray();
        $ret['title'] = $GLOBALS['myts']->htmlSpecialChars($ret['title']);

        return $ret;
    }

    function getURL($uid, $num, $pos, $view)
    {
        $module_handler = xoops_getHandler('module');
        $config_handler = xoops_getHandler('config');
        if (!isset($GLOBALS['myalbumModule'])) {
            $GLOBALS['myalbumModule'] = $module_handler->getByDirname($mydirname);
        }
        if (!isset($GLOBALS['myalbumModuleConfig'])) {
            $GLOBALS['myalbumModuleConfig'] = $config_handler->getConfigList($GLOBALS['myalbumModule']->getVar('mid'));
        }
        if ($GLOBALS['myalbumModuleConfig']['htaccess']) {
            return XOOPS_URL . '/' . $GLOBALS['myalbumModuleConfig']['baseurl'] . '/' . str_replace(
                array('_', ' ', ')', '(', '&', '#'),
                '-',
                $this->getVar('title')
            ) . '/cat,' . $this->getVar('cid') . ',' . $uid . ',' . $num . ',' . $pos . ',' . $view . $GLOBALS['myalbumModuleConfig']['endofurl'];
        } else {
            return XOOPS_URL . '/modules/' . $GLOBALS['mydirname'] . '/viewcat.php?cid=' . $this->getVar('cid') . '&uid=' . $uid . '&num=' . $num
            . '&pos=' . $pos . '&view=' . $view;
        }

    }

    function getRSSURL($uid, $num, $pos, $view)
    {
        $module_handler = xoops_getHandler('module');
        $config_handler = xoops_getHandler('config');
        if (!isset($GLOBALS['myalbumModule'])) {
            $GLOBALS['myalbumModule'] = $module_handler->getByDirname($mydirname);
        }
        if (!isset($GLOBALS['myalbumModuleConfig'])) {
            $GLOBALS['myalbumModuleConfig'] = $config_handler->getConfigList($GLOBALS['myalbumModule']->getVar('mid'));
        }
        if ($GLOBALS['myalbumModuleConfig']['htaccess']) {
            return
                XOOPS_URL . '/' . $GLOBALS['myalbumModuleConfig']['baseurl'] . '/' . xoops_sef($this->getVar('title')) . '/rss,' . $cid . ',' . $uid
                . ',' . $num . ',' . $pos . ',' . $view . $GLOBALS['myalbumModuleConfig']['endofrss'];
        } else {
            return
                XOOPS_URL . '/modules/' . $GLOBALS['mydirname'] . '/rss.php?cid=' . $this->getVar('cid') . '&uid=' . $uid . '&num=' . $num . '&pos='
                . $pos . '&view=' . $view;
        }
    }

}

/**
 * XOOPS policies handler class.
 * This class is responsible for providing data access mechanisms to the data source
 * of XOOPS user class objects.
 *
 * @author  Simon Roberts <simon@chronolabs.coop>
 * @package kernel
 */
class MyalbumCatHandler extends XoopsPersistableObjectHandler
{
    function __construct(&$db)
    {
        $this->db = $db;
        parent::__construct($db, $GLOBALS['table_cat'], 'MyalbumCat', 'cid', 'title');
    }

    function prefixDepth($cid, $depth = 0)
    {
        $cat = parent::get($cid);
        $depth++;
        if ($cat->getVar('pid') != 0) {
            $depth = $this->prefixDepth($cat->getVar('pid'), $depth);
        } else {
            $depth--;

            return $depth;
        }

        return $depth;
    }

}

class Myalbum0CatHandler extends MyalbumCatHandler
{
    function __construct(&$db)
    {
        parent::__construct($db);
    }
}

class Myalbum1CatHandler extends MyalbumCatHandler
{
    function __construct(&$db)
    {
        parent::__construct($db);
    }
}

class Myalbum2CatHandler extends MyalbumCatHandler
{
    function __construct(&$db)
    {
        parent::__construct($db);
    }
}
