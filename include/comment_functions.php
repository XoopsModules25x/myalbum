<?php


use XoopsModules\Myalbum\{
    Helper,
    PhotosHandler
};

/** @var Helper $helper */
/** @var  PhotosHandler $photosHandler */

if (!defined('MYALBUM_COMMENT_FUNCTIONS_INCLUDED')) {
    define('MYALBUM_COMMENT_FUNCTIONS_INCLUDED', 1);

    // comment callback functions

    /**
     * @param $lid
     * @param $total_num
     *
     * @return mixed
     */
    function myalbum_comments_update($lid, $total_num)
    {
        $helper = Helper::getInstance();
        $photosHandler = $helper->getHandler('Photos');
        $photo         = $photosHandler->get($lid);
        $photo->setVar('comments', $total_num);

        return $photosHandler->insert($photo, true);
    }

    /**
     * @param $comment
     */
    function myalbum_comments_approve(&$comment)
    {
        // notification mail here
    }
}
