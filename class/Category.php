<?php

namespace XoopsModules\Myalbum;

require_once \dirname(__DIR__) . '/include/read_configs.php';

/**
 *
 * @author    Simon Roberts <simon@xoops.org>
 * @copyright copyright (c) 2009-2003 XOOPS.org
 * @package   kernel
 */
class Category extends \XoopsObject
{
    /**
     * @param null $id
     */
    public function __construct($id = null)
    {
        $this->initVar('cid', \XOBJ_DTYPE_INT, null, false);
        $this->initVar('pid', \XOBJ_DTYPE_INT, null, false);
        $this->initVar('title', \XOBJ_DTYPE_TXTBOX, null, false, 50);
        $this->initVar('imgurl', \XOBJ_DTYPE_TXTBOX, null, false, 150);
        $this->initVar('weight', \XOBJ_DTYPE_INT, null, false);
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
        $helper = Helper::getInstance();
        $moduleDirName = \basename(\dirname(__DIR__));
        /** @var \XoopsModuleHandler $moduleHandler */
        $moduleHandler = \xoops_getHandler('module');
        /** @var \XoopsConfigHandler $configHandler */
        $configHandler = \xoops_getHandler('config');
        if (!isset($GLOBALS['myalbumModule'])) {
            $GLOBALS['myalbumModule'] = $moduleHandler->getByDirname($moduleDirName);
        }
//        if (!isset($GLOBALS['myalbumModuleConfig'])) {
//            $GLOBALS['myalbumModuleConfig'] = $configHandler->getConfigList($GLOBALS['myalbumModule']->getVar('mid'));
//        }
        if ($helper->getConfig('htaccess')) {
            return XOOPS_URL . '/' . $helper->getConfig('baseurl') . '/' . \str_replace(
                    [
                        '_',
                        ' ',
                        ')',
                        '(',
                        '&',
                        '#',
                    ],
                    '-',
                    $this->getVar('title')
                ) . '/cat,' . $this->getVar('cid') . ',' . $uid . ',' . $num . ',' . $pos . ',' . $view . $helper->getConfig('endofurl');
        }

        return Helper::getInstance()->url() . 'viewcat.php?cid=' . $this->getVar('cid') . '&uid=' . $uid . '&num=' . $num . '&pos=' . $pos . '&view=' . $view;
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
        $helper = Helper::getInstance();
        $moduleDirName = \basename(\dirname(__DIR__));
        /** @var \XoopsModuleHandler $moduleHandler */
        $moduleHandler = \xoops_getHandler('module');
        /** @var \XoopsConfigHandler $configHandler */
        $configHandler = \xoops_getHandler('config');
        if (!isset($GLOBALS['myalbumModule'])) {
            $GLOBALS['myalbumModule'] = $moduleHandler->getByDirname($moduleDirName);
        }
//        if (!isset($GLOBALS['myalbumModuleConfig'])) {
//            $GLOBALS['myalbumModuleConfig'] = $configHandler->getConfigList($GLOBALS['myalbumModule']->getVar('mid'));
//        }
        if ($helper->getConfig('htaccess')) {
            return XOOPS_URL . '/' . $helper->getConfig('baseurl') . '/' . Utility::xoops_sef($this->getVar('title')) . '/rss,' . $cid . ',' . $uid . ',' . $num . ',' . $pos . ',' . $view . $helper->getConfig('endofrss');
        }

        return Helper::getInstance()->url() . 'rss.php?cid=' . $this->getVar('cid') . '&uid=' . $uid . '&num=' . $num . '&pos=' . $pos . '&view=' . $view;
    }
}
