<?php

// defined('XOOPS_ROOT_PATH') || die('Restricted access');

$myalbum_orders = [
    'cidA'    => ['cid ASC', _ALBM_CIDASC],
    'lidA'    => ['lid ASC', _ALBM_LIDASC],
    'titleA'  => ['title ASC', _ALBM_TITLEATOZ],
    'dateA'   => ['date ASC', _ALBM_DATEOLD],
    'hitsA'   => ['hits ASC', _ALBM_POPULARITYLTOM],
    'ratingA' => ['rating ASC', _ALBM_RATINGLTOH],
    'cidD'    => ['cid DESC', _ALBM_CIDDESC],
    'lidD'    => ['lid DESC', _ALBM_LIDDESC],
    'titleD'  => ['title DESC', _ALBM_TITLEZTOA],
    'dateD'   => ['date DESC', _ALBM_DATENEW],
    'hitsD'   => ['hits DESC', _ALBM_POPULARITYMTOL],
    'ratingD' => ['rating DESC', _ALBM_RATINGHTOL]
];
