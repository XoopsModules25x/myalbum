<?php

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
        /** @var MyalbumPhotosHandler $photosHandler */
        $photosHandler = xoops_getModuleHandler('photos', $GLOBALS['mydirname']);
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
