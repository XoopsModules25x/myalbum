<?php
// defined('XOOPS_ROOT_PATH') || die('Restricted access');

include __DIR__ . '/../include/read_configs.php';

/**
 * Class for Blue Room Xcenter
 *
 * @author    Simon Roberts <simon@xoops.org>
 * @copyright copyright (c) 2009-2003 XOOPS.org
 * @package   kernel
 */
class MyalbumCat extends XoopsObject
{
    /**
     * @param null $id
     */
    public function __construct($id = null)
    {
        $this->initVar('cid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('pid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('title', XOBJ_DTYPE_TXTBOX, null, false, 50);
        $this->initVar('imgurl', XOBJ_DTYPE_TXTBOX, null, false, 150);
        $this->initVar('weight', XOBJ_DTYPE_INT, null, false);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $ret          = parent::toArray();
        $ret['title'] = $GLOBALS['myts']->htmlSpecialChars($ret['title']);

        return $ret;
    }

    /**
     * @param $uid
     * @param $num
     * @param $pos
     * @param $view
     *
     * @return string
     */
    public function getURL($uid, $num, $pos, $view)
    {
        /** @var XoopsModuleHandler $moduleHandler */
        $moduleHandler = xoops_getHandler('module');
        $configHandler = xoops_getHandler('config');
        if (!isset($GLOBALS['myalbumModule'])) {
            $GLOBALS['myalbumModule'] = $moduleHandler->getByDirname($moduleDirName);
        }
        if (!isset($GLOBALS['myalbumModuleConfig'])) {
            $GLOBALS['myalbumModuleConfig'] = $configHandler->getConfigList($GLOBALS['myalbumModule']->getVar('mid'));
        }
        if ($GLOBALS['myalbumModuleConfig']['htaccess']) {
            return XOOPS_URL . '/' . $GLOBALS['myalbumModuleConfig']['baseurl'] . '/' . str_replace([
                                                                                                        '_',
                                                                                                        ' ',
                                                                                                        ')',
                                                                                                        '(',
                                                                                                        '&',
                                                                                                        '#'
                                                                                                    ], '-', $this->getVar('title')) . '/cat,' . $this->getVar('cid') . ',' . $uid . ',' . $num . ',' . $pos . ',' . $view . $GLOBALS['myalbumModuleConfig']['endofurl'];
        } else {
            return XOOPS_URL . '/modules/' . $GLOBALS['mydirname'] . '/viewcat.php?cid=' . $this->getVar('cid') . '&uid=' . $uid . '&num=' . $num . '&pos=' . $pos . '&view=' . $view;
        }
    }

    /**
     * @param $uid
     * @param $num
     * @param $pos
     * @param $view
     *
     * @return string
     */
    public function getRSSURL($uid, $num, $pos, $view)
    {
        /** @var XoopsModuleHandler $moduleHandler */
        $moduleHandler = xoops_getHandler('module');
        $configHandler = xoops_getHandler('config');
        if (!isset($GLOBALS['myalbumModule'])) {
            $GLOBALS['myalbumModule'] = $moduleHandler->getByDirname($moduleDirName);
        }
        if (!isset($GLOBALS['myalbumModuleConfig'])) {
            $GLOBALS['myalbumModuleConfig'] = $configHandler->getConfigList($GLOBALS['myalbumModule']->getVar('mid'));
        }
        if ($GLOBALS['myalbumModuleConfig']['htaccess']) {
            return XOOPS_URL . '/' . $GLOBALS['myalbumModuleConfig']['baseurl'] . '/' . xoops_sef($this->getVar('title')) . '/rss,' . $cid . ',' . $uid . ',' . $num . ',' . $pos . ',' . $view . $GLOBALS['myalbumModuleConfig']['endofrss'];
        } else {
            return XOOPS_URL . '/modules/' . $GLOBALS['mydirname'] . '/rss.php?cid=' . $this->getVar('cid') . '&uid=' . $uid . '&num=' . $num . '&pos=' . $pos . '&view=' . $view;
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
    /**
     * @param null|\XoopsDatabase $db
     */
    public function __construct(\XoopsDatabase $db)
    {
        $this->db = $db;
        parent::__construct($db, $GLOBALS['table_cat'], 'MyalbumCat', 'cid', 'title');
    }

    /**
     * @param     $cid
     * @param int $depth
     *
     * @return int
     */
    public function prefixDepth($cid, $depth = 0)
    {
        $cat = parent::get($cid);
        ++$depth;
        if (0 != $cat->getVar('pid')) {
            $depth = $this->prefixDepth($cat->getVar('pid'), $depth);
        } else {
            $depth--;

            return $depth;
        }

        return $depth;
    }
}

/**
 * Class Myalbum0CatHandler
 */
class Myalbum0CatHandler extends MyalbumCatHandler
{
    /**
     * @param null|\XoopsDatabase $db
     */
    public function __construct(\XoopsDatabase $db)
    {
        parent::__construct($db);
    }
}

/**
 * Class Myalbum1CatHandler
 */
class Myalbum1CatHandler extends MyalbumCatHandler
{
    /**
     * @param null|\XoopsDatabase $db
     */
    public function __construct(\XoopsDatabase $db)
    {
        parent::__construct($db);
    }
}

/**
 * Class Myalbum2CatHandler
 */
class Myalbum2CatHandler extends MyalbumCatHandler
{
    /**
     * @param null|\XoopsDatabase $db
     */
    public function __construct(\XoopsDatabase $db)
    {
        parent::__construct($db);
    }
}
