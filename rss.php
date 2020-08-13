<?php

use Xmf\Request;
use XoopsModules\Myalbum\{
    CategoryHandler,
    Helper,
    PhotosHandler,
    TextHandler,
    Utility
};
/** @var Helper $helper */
/** @var PhotosHandler $photosHandler */
/** @var TextHandler $textHandler */
/** @var CategoryHandler $catHandler */

require_once __DIR__ . '/header.php';

// GET variables
$cid = Request::getInt('cid', 0, 'GET');
$uid = Request::getInt('uid', 0, 'GET');
$num = Request::getInt('num', (int)$myalbum_perpage, 'GET');
if ($num < 1) {
    $num = 10;
}
$pos  = Request::getInt('pos', 0, 'GET');
$view = Request::getString('view', $myalbum_viewcattype, 'GET');

$photosHandler = $helper->getHandler('Photos');
$textHandler = $helper->getHandler('Text');
$catHandler = $helper->getHandler('Category');
if ($GLOBALS['myalbumModuleConfig']['htaccess']) {
    if (0 == $cid) {
        $url = XOOPS_URL . '/' . $GLOBALS['myalbumModuleConfig']['baseurl'] . '/rss,' . $cid . ',' . $uid . ',' . $num . ',' . $pos . ',' . $view . $GLOBALS['myalbumModuleConfig']['endofrss'];
    } else {
        $cat = $catHandler->get($cid);
        $url = $cat->getRSSURL($uid, $num, $pos, $view);
    }

    if (!mb_strpos($url, $_SERVER['REQUEST_URI'])) {
        header('HTTP/1.1 301 Moved Permanently');
        header('Location: ' . $url);
    }
}

$GLOBALS['xoopsLogger']->activated = false;

if (function_exists('mb_http_output')) {
    mb_http_output('pass');
}
header('Content-Type:text/xml; charset=utf-8');

require_once $GLOBALS['xoops']->path('class/template.php');
$GLOBALS['xoopsTpl']                 = new \XoopsTpl();
$GLOBALS['xoopsTpl']->caching        = 2;
$GLOBALS['xoopsTpl']->cache_lifetime = 3600;
if (!$GLOBALS['xoopsTpl']->is_cached('db:' . $GLOBALS['mydirname'] . '_rss.tpl')) {
    xoops_load('XoopsLocal');
    $GLOBALS['xoopsTpl']->assign('channel_title', XoopsLocal::convert_encoding(htmlspecialchars($xoopsConfig['sitename'] . (is_object($cat) ? ' : ' . $cat->getVar('title') . ' : ' . $GLOBALS['myalbumModule']->getVar('name') : ' : ' . $GLOBALS['myalbumModule']->getVar('name')), ENT_QUOTES)));
    $GLOBALS['xoopsTpl']->assign('channel_link', XOOPS_URL . '/');
    $GLOBALS['xoopsTpl']->assign('channel_desc', XoopsLocal::convert_encoding(htmlspecialchars($xoopsConfig['slogan'], ENT_QUOTES)));
    $GLOBALS['xoopsTpl']->assign('channel_lastbuild', formatTimestamp(time(), 'rss'));
    $GLOBALS['xoopsTpl']->assign('channel_webmaster', checkEmail($xoopsConfig['adminmail'], true));
    $GLOBALS['xoopsTpl']->assign('channel_editor', checkEmail($xoopsConfig['adminmail'], true));
    $GLOBALS['xoopsTpl']->assign('channel_category', $GLOBALS['myalbumModule']->getVar('name'));
    $GLOBALS['xoopsTpl']->assign('channel_generator', mb_strtoupper($GLOBALS['myalbumModule']->getVar('dirname')));
    $GLOBALS['xoopsTpl']->assign('channel_language', _LANGCODE);
    $GLOBALS['xoopsTpl']->assign('image_url', XOOPS_URL . '/images/logo.png');
    $dimension = getimagesize(XOOPS_ROOT_PATH . '/images/logo.png');
    if (empty($dimension[0])) {
        $width = 88;
    } else {
        $width = ($dimension[0] > 144) ? 144 : $dimension[0];
    }
    if (empty($dimension[1])) {
        $height = 31;
    } else {
        $height = ($dimension[1] > 400) ? 400 : $dimension[1];
    }
    $GLOBALS['xoopsTpl']->assign('image_width', $width);
    $GLOBALS['xoopsTpl']->assign('image_height', $height);
    require_once XOOPS_ROOT_PATH . "/modules/$moduleDirName/include/photo_orders.php";
    if (Request::hasVar('orderby', 'GET') && isset($myalbum_orders[$_GET['orderby']])) {
        $orderby = $_GET['orderby'];
    } else {
        if (isset($myalbum_orders[$myalbum_defaultorder])) {
            $orderby = $myalbum_defaultorder;
        } else {
            $orderby = 'titleA';
        }
    }

    if ($cid > 0) {
        $cat = $catHandler->get($cid);
        foreach ($GLOBALS['cattree']->getAllChild($cid) as $index => $child) {
            $cids[$child->getVar('cid')] = $child->getVar('cid');
        }
        $cids[]   = $cid;
        $criteria = new \CriteriaCompo(new \Criteria('status', '0', '>'));
        $photo_total_sum = Utility::getTotalCount($cids, $criteria);
        $sub_title       = preg_replace("/\'\>/", "'><img src='$mod_url/assets/images/folder16.gif' alt=''>", $GLOBALS['cattree']->getNicePathFromId($cid, 'title', "viewcat.php?num=$num"));
        $sub_title       = preg_replace('/^(.+)folder16/', '$1folder_open', $sub_title);
        $criteria->add(new \Criteria('cid', $cid));
    } elseif (0 != $uid) {
        // This means 'my photo'
        if ($uid < 0) {
            $criteria = new \CriteriaCompo(new \Criteria('status', '0', '>'));
        } else {
            $criteria = new \CriteriaCompo(new \Criteria('status', '0', '>'));
            $criteria->add(new \Criteria('`submitter`', $uid));
        }
    } else {
        $criteria = new \CriteriaCompo(new \Criteria('status', '0', '>'));
    }

    $criteria->setOrder($myalbum_orders[$orderby][0]);
    $criteria->setStart($pos);
    $criteria->setLimit($num);

    // Display photos
    foreach ($photosHandler->getObjects($criteria, true) as $lid => $photo) {
        $text = $textHandler->get($lid);
        $cat  = $catHandler->get($photo->getVar('cid'));
        $GLOBALS['xoopsTpl']->append(
            'items',
            [
                'title'       => XoopsLocal::convert_encoding(htmlspecialchars($photo->getVar('title'), ENT_QUOTES)),
                'category'    => XoopsLocal::convert_encoding(htmlspecialchars($cat->getVar('title'), ENT_QUOTES)),
                'link'        => XoopsLocal::convert_encoding(htmlspecialchars($photo->getURL(), ENT_QUOTES | ENT_HTML5)),
                'guid'        => XoopsLocal::convert_encoding(htmlspecialchars($photo->getURL(), ENT_QUOTES | ENT_HTML5)),
                'pubdate'     => formatTimestamp($photo->getVar('date'), 'rss'),
                'description' => XoopsLocal::convert_encoding(htmlspecialchars(sprintf(_ALBM_RSS_DESC, $photo->getThumbsURL(), $GLOBALS['myts']->displayTarea($text->getVar('description'), 1, 1, 1, 1, 1, 1)), ENT_QUOTES)),
            ]
        );
    }
}
$GLOBALS['xoopsTpl']->display('db:' . $GLOBALS['mydirname'] . '_rss.tpl');
