<?php

namespace XoopsModules\Myalbum;



require_once \dirname(__DIR__) . '/include/read_configs.php';

/**
 * XOOPS policies handler class.
 * This class is responsible for providing data access mechanisms to the data source
 * of XOOPS user class objects.
 *
 * @author  Simon Roberts <simon@chronolabs.coop>
 * @package kernel
 */
class PhotosHandler extends \XoopsPersistableObjectHandler
{
    public $_table   = null;
    public $_dirname = null;

    /**
     * @param null|\XoopsDatabase $db
     */
    public function __construct(\XoopsDatabase $db = null)
    {
        $this->db       = $db;
        $this->_dirname = $GLOBALS['mydirname'];
        $this->_table   = $GLOBALS['table_photos'];
        parent::__construct($db, $this->_table, Photos::class, 'lid', 'title');
    }

    /**
     *
     * @param null
     *
     * @return self
     */
//    public static function getInstance()
//    {
//        static $instance = false;
//        if (!$instance) {
//            $instance = new self();
//        }
//    }

    /**
     * @param     $ids
     * @param int $status
     *
     * @return bool
     */
    public function setStatus($ids, $status = 1)
    {
        if (empty($ids)) {
            return false;
        }

        if (\is_array($ids)) {
            $where = "`lid` IN ('" . \implode("','", $ids) . "')";
        } else {
            $where = "`lid` = '$ids'";
        }

        $this->db->query('UPDATE ' . $this->db->prefix($this->_table) . " SET `status`='$status' WHERE $where");

        switch ($status) {
            case 1:
                $helper = Helper::getInstance();
                /** @var CategoryHandler $catHandler */
                $catHandler = $helper->getHandler('Category');
                $cats  = $catHandler->getObjects(null, true);
                // Trigger Notification
                /** @var \XoopsNotificationHandler $notificationHandler */
                $notificationHandler = \xoops_getHandler('notification');
                $criteria            = new \Criteria('lid', "('" . \implode("','", $ids) . "')", 'IN');
                $photos              = $this->getObjects($criteria, true);
                foreach ($photos as $lid => $photo) {
                    $notificationHandler->triggerEvent(
                        'global',
                        0,
                        'new_photo',
                        [
                            'PHOTO_TITLE' => $photo->getVar('title'),
                            'PHOTO_URI'   => $photo->getURL(),
                        ]
                    );
                    if ($photo->getVar('title') > 0 && \is_object($cats[$photo->getVar('cid')])) {
                        $notificationHandler->triggerEvent(
                            'category',
                            $photo->getVar('cid'),
                            'new_photo',
                            [
                                'PHOTO_TITLE'    => $photo->getVar('title'),
                                'CATEGORY_TITLE' => $cats[$photo->getVar('cid')]->getVar('title'),
                                'PHOTO_URI'      => $photo->getURL(),
                            ]
                        );
                    }
                }
                break;
        }

        return true;
    }

    /**
     * @param $ids
     *
     * @return bool
     */
    public function deletePhotos($ids)
    {
        foreach ($ids as $lid) {
            @$this->delete($lid, true);
        }

        return true;
    }

    /**
     * @param \XoopsObject|int $photo
     * @param bool         $force
     *
     * @return bool
     */
    public function delete(\XoopsObject $photo, $force = true)
    {
        if (\is_numeric($photo)) {
            $photo = $this->get($photo);
        }

        if (!\is_a($photo, 'Photos')) {
            return false;
        }

        \xoops_comment_delete($GLOBALS['myalbum_mid'], $photo->getVar('lid'));
        \xoops_notification_deletebyitem($GLOBALS['myalbum_mid'], 'photo', $photo->getVar('lid'));

        \unlink($GLOBALS['photos_dir'] . DS . $photo->getVar('lid') . '.' . $photo->getVar('ext'));
        \unlink($GLOBALS['photos_dir'] . DS . $photo->getVar('lid') . '.gif');
        \unlink($GLOBALS['thumbs_dir'] . DS . $photo->getVar('lid') . '.' . $photo->getVar('ext'));
        \unlink($GLOBALS['thumbs_dir'] . DS . $photo->getVar('lid') . '.gif');

        $helper = Helper::getInstance();

        /** @var  VotedataHandler $votedataHandler */
        $votedataHandler = $helper->getHandler('Votedata');
        /** @var TextHandler $textHandler */
        $textHandler = $helper->getHandler('Text');
        /** @var CommentsHandler $commentsHandler */
        $commentsHandler = $helper->getHandler('Comments');
        $criteria        = new \Criteria('lid', $photo->getVar('lid'));
        $votedataHandler->deleteAll($criteria, $force);
        $textHandler->deleteAll($criteria, $force);

        return parent::delete($photo, $force);
    }

    /**
     * @param null $criteria
     *
     * @return int
     */
    public function getCountDeadPhotos($criteria = null)
    {
        $objects = $this->getObjects($criteria, true);
        $i       = 0;
        foreach ($objects as $lid => $object) {
            if (!\is_readable($GLOBALS['photos_dir'] . DS . $lid . '.' . $object->getVar('ext'))) {
                ++$i;
            }
        }

        return $i;
    }

    /**
     * @param null $criteria
     *
     * @return int
     */
    public function getCountDeadThumbs($criteria = null)
    {
        $objects = $this->getObjects($criteria, true);
        $i       = 0;
        foreach ($objects as $lid => $object) {
            if (!\is_readable($GLOBALS['thumbs_dir'] . DS . $lid . '.' . $object->getVar('ext'))) {
                ++$i;
            }
        }

        return $i;
    }

    /**
     * @param null $criteria
     *
     * @return array
     */
    public function getDeadPhotos($criteria = null)
    {
        $objects = $this->getObjects($criteria, true);
        foreach ($objects as $lid => $object) {
            if (\is_readable($GLOBALS['photos_dir'] . DS . $lid . '.' . $object->getVar('ext'))) {
                unset($objects[$lid]);
            }
        }

        return $objects;
    }

    /**
     * @param null $criteria
     *
     * @return array
     */
    public function getDeadThumbs($criteria = null)
    {
        $objects = $this->getObjects($criteria, true);
        foreach ($objects as $lid => $object) {
            if (\is_readable($GLOBALS['thumbs_dir'] . DS . $lid . '.' . $object->getVar('ext'))) {
                unset($objects[$lid]);
            }
        }

        return $objects;
    }
}
