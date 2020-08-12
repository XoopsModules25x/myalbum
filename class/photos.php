<?php

namespace XoopsModules\Myalbum;



require_once \dirname(__DIR__) . '/include/read_configs.php';

/**
 * Class for Blue Room Xcenter
 *
 * @author    Simon Roberts <simon@xoops.org>
 * @copyright copyright (c) 2009-2003 XOOPS.org
 * @package   kernel
 */
class Photos extends \XoopsObject
{
    /**
     * MyalbumPhotos constructor.
     * @param null $id
     */
    public function __construct($id = null)
    {
        $this->initVar('lid', \XOBJ_DTYPE_INT, null, false);
        $this->initVar('cid', \XOBJ_DTYPE_INT, null, false);
        $this->initVar('title', \XOBJ_DTYPE_TXTBOX, null, false, 100);
        $this->initVar('ext', \XOBJ_DTYPE_TXTBOX, null, false, 10);
        $this->initVar('res_x', \XOBJ_DTYPE_INT, null, false);
        $this->initVar('res_y', \XOBJ_DTYPE_INT, null, false);
        $this->initVar('submitter', \XOBJ_DTYPE_INT, null, false);
        $this->initVar('status', \XOBJ_DTYPE_INT, null, false);
        $this->initVar('date', \XOBJ_DTYPE_INT, null, false);
        $this->initVar('hits', \XOBJ_DTYPE_INT, null, false);
        $this->initVar('rating', \XOBJ_DTYPE_DECIMAL, null, false);
        $this->initVar('votes', \XOBJ_DTYPE_INT, null, false);
        $this->initVar('comments', \XOBJ_DTYPE_INT, null, false);
        $this->initVar('tags', \XOBJ_DTYPE_TXTBOX, null, false, 255);
    }

    /**
     * @return string
     */
    public function getURL()
    {
        /** @var \XoopsModuleHandler $moduleHandler */
        $moduleHandler = \xoops_getHandler('module');
        $configHandler = \xoops_getHandler('config');
        if (!isset($GLOBALS['myalbumModule'])) {
            $GLOBALS['myalbumModule'] = $moduleHandler->getByDirname($moduleDirName);
        }
        if (!isset($GLOBALS['myalbumModuleConfig'])) {
            $GLOBALS['myalbumModuleConfig'] = $configHandler->getConfigList($GLOBALS['myalbumModule']->getVar('mid'));
        }

        if ($GLOBALS['myalbumModuleConfig']['htaccess']) {
            /** @var CatHandler $catHandler */
            $catHandler = $helper->getHandler('Category');
            $cat        = $catHandler->get($this->getVar('cid'));
            $url        = XOOPS_URL . '/' . $GLOBALS['myalbumModuleConfig']['baseurl'] . '/' . \str_replace(
                    [
                        '_',
                        ' ',
                        ')',
                        '(',
                        '&',
                        '#',
                    ],
                    '-',
                    $cat->getVar('title')
                ) . '/' . \str_replace(
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
                          ) . '/' . $this->getVar('lid') . ',' . $this->getVar('cid') . $GLOBALS['myalbumModuleConfig']['endofurl'];
        } else {
            $url = $GLOBALS['mod_url'] . '/photo.php?lid=' . $this->getVar('lid') . '&cid=' . $this->getVar('cid');
        }

        return $url;
    }

    /**
     * @return string
     */
    public function getEditURL()
    {
        /** @var \XoopsModuleHandler $moduleHandler */
        $moduleHandler = \xoops_getHandler('module');
        $configHandler = \xoops_getHandler('config');
        if (!isset($GLOBALS['myalbumModule'])) {
            $GLOBALS['myalbumModule'] = $moduleHandler->getByDirname($moduleDirName);
        }
        if (!isset($GLOBALS['myalbumModuleConfig'])) {
            $GLOBALS['myalbumModuleConfig'] = $configHandler->getConfigList($GLOBALS['myalbumModule']->getVar('mid'));
        }

        if ($GLOBALS['myalbumModuleConfig']['htaccess']) {
            /** @var CatHandler $catHandler */
            $catHandler = $helper->getHandler('Category');
            $cat        = $catHandler->get($this->getVar('cid'));
            $url        = XOOPS_URL . '/' . $GLOBALS['myalbumModuleConfig']['baseurl'] . '/' . \str_replace(
                    [
                        '_',
                        ' ',
                        ')',
                        '(',
                        '&',
                        '#',
                    ],
                    '-',
                    $cat->getVar('title')
                ) . '/' . \str_replace(
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
                          ) . '/edit,' . $this->getVar('lid') . ',' . $this->getVar('cid') . $GLOBALS['myalbumModuleConfig']['endofurl'];
        } else {
            $url = $GLOBALS['mod_url'] . '/editphoto.php?lid=' . $this->getVar('lid') . '&cid=' . $this->getVar('cid');
        }

        return $url;
    }

    /**
     * @return string
     */
    public function getRateURL()
    {
        /** @var \XoopsModuleHandler $moduleHandler */
        $moduleHandler = \xoops_getHandler('module');
        $configHandler = \xoops_getHandler('config');
        if (!isset($GLOBALS['myalbumModule'])) {
            $GLOBALS['myalbumModule'] = $moduleHandler->getByDirname($moduleDirName);
        }
        if (!isset($GLOBALS['myalbumModuleConfig'])) {
            $GLOBALS['myalbumModuleConfig'] = $configHandler->getConfigList($GLOBALS['myalbumModule']->getVar('mid'));
        }

        if ($GLOBALS['myalbumModuleConfig']['htaccess']) {
            /** @var CatHandler $catHandler */
            $catHandler = $helper->getHandler('Category');
            $cat        = $catHandler->get($this->getVar('cid'));
            $url        = XOOPS_URL . '/' . $GLOBALS['myalbumModuleConfig']['baseurl'] . '/' . \str_replace(
                    [
                        '_',
                        ' ',
                        ')',
                        '(',
                        '&',
                        '#',
                    ],
                    '-',
                    $cat->getVar('title')
                ) . '/' . \str_replace(
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
                          ) . '/rate,' . $this->getVar('lid') . ',' . $this->getVar('cid') . $GLOBALS['myalbumModuleConfig']['endofurl'];
        } else {
            $url = $GLOBALS['mod_url'] . '/ratephoto.php?lid=' . $this->getVar('lid') . '&cid=' . $this->getVar('cid');
        }

        return $url;
    }

    /**
     * @return string
     */
    public function getThumbsURL()
    {
        /** @var \XoopsModuleHandler $moduleHandler */
        $moduleHandler = \xoops_getHandler('module');
        $configHandler = \xoops_getHandler('config');
        if (!isset($GLOBALS['myalbumModule'])) {
            $GLOBALS['myalbumModule'] = $moduleHandler->getByDirname($moduleDirName);
        }
        if (!isset($GLOBALS['myalbumModuleConfig'])) {
            $GLOBALS['myalbumModuleConfig'] = $configHandler->getConfigList($GLOBALS['myalbumModule']->getVar('mid'));
        }

        $url = $GLOBALS['thumbs_url'] . '/' . $this->getVar('lid') . '.' . $this->getVar('ext');

        return $url;
    }

    /**
     * @return string
     */
    public function getPhotoURL()
    {
        /** @var \XoopsModuleHandler $moduleHandler */
        $moduleHandler = \xoops_getHandler('module');
        $configHandler = \xoops_getHandler('config');
        if (!isset($GLOBALS['myalbumModule'])) {
            $GLOBALS['myalbumModule'] = $moduleHandler->getByDirname($moduleDirName);
        }
        if (!isset($GLOBALS['myalbumModuleConfig'])) {
            $GLOBALS['myalbumModuleConfig'] = $configHandler->getConfigList($GLOBALS['myalbumModule']->getVar('mid'));
        }

        $url = $GLOBALS['photos_url'] . '/' . $this->getVar('lid') . '.' . $this->getVar('ext');

        return $url;
    }

    /**
     * @param bool $justVar
     *
     * @return mixed
     */
    public function toArray($justVar = false)
    {
        if (true === $justVar) {
            return parent::toArray();
        }
        /** @var CatHandler $catHandler */
        $catHandler = $helper->getHandler('Category');
        /** @var TextHandler $textHandler */
        $textHandler = $helper->getHandler('Text');
        $userHandler = \xoops_getHandler('user');
        //mb        $statusHandler = xoops_getModuleHandler('status');
        $ret['photo'] = parent::toArray();

        $cat = $catHandler->get($this->getVar('cid'));

        if (\is_a($cat, 'Category')) {
            $ret['cat'] = $cat->toArray();
        }

        $text = $textHandler->get($this->getVar('lid'));
        if (\is_a($text, 'Text')) {
            $ret['text'] = $text->toArray();
        }

        $user = $userHandler->get($this->getVar('submitter'));
        //mb        $ret['status'] = $statusHandler->get($this->getVar('status'));
        $ret['status'] = $this->getVar('status'); //mb
        $ret['user']   = $user->toArray();

        return $ret;
    }

    /**
     * @param int $value
     *
     * @return mixed
     */
    public function increaseHits($value = 1)
    {
        return $GLOBALS['xoopsDB']->queryF('UPDATE ' . $GLOBALS['xoopsDB']->prefix($GLOBALS['table_photos']) . ' SET hits=hits+' . $value . " WHERE `lid`='" . $this->getVar('lid') . "' AND `status` > 0");
    }
}
