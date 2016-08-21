<?php

if (!defined('XOOPS_ROOT_PATH')) {
    exit;
}

$myalbum_orders = array(
    'cidA'    => array('cid ASC', _ALBM_CIDASC),
    'lidA'    => array('lid ASC', _ALBM_LIDASC),
    'titleA'  => array('title ASC', _ALBM_TITLEATOZ),
    'dateA'   => array('date ASC', _ALBM_DATEOLD),
    'hitsA'   => array('hits ASC', _ALBM_POPULARITYLTOM),
    'ratingA' => array('rating ASC', _ALBM_RATINGLTOH),
    'cidD'    => array('cid DESC', _ALBM_CIDDESC),
    'lidD'    => array('lid DESC', _ALBM_LIDDESC),
    'titleD'  => array('title DESC', _ALBM_TITLEZTOA),
    'dateD'   => array('date DESC', _ALBM_DATENEW),
    'hitsD'   => array('hits DESC', _ALBM_POPULARITYMTOL),
    'ratingD' => array('rating DESC', _ALBM_RATINGHTOL)
);
