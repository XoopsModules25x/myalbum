<?php

if ( ! defined( 'MYALBUM_COMMENT_FUNCTIONS_INCLUDED' ) ) {

define( 'MYALBUM_COMMENT_FUNCTIONS_INCLUDED' , 1 ) ;

// comment callback functions

function myalbum_comments_update( $lid , $total_num )
{
    $photos_handler = xoops_getmodulehandler('photos', $GLOBALS['mydirname']);
    $photo = $photos_handler->get($lid);
    $photo->setVar('comments', $total_num);

    return $photos_handler->insert($photo, true);
}

function myalbum_comments_approve( &$comment )
{
    // notification mail here
}

}
