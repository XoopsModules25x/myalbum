<?php
// ------------------------------------------------------------------------- //
//                      myAlbum-P - XOOPS photo album                        //
//                        <http://www.peak.ne.jp>                           //
// ------------------------------------------------------------------------- //

use Xmf\Request;
use XoopsModules\Myalbum\{
    CategoryHandler,
    Helper,
    Preview,
    PhotosHandler,
    Utility
};

/** @var Helper $helper */
/** @var PhotosHandler $photosHandler */
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
$view = $_GET['view'] ?? $myalbum_viewcattype;

$photosHandler = $helper->getHandler('Photos');
$catHandler    = $helper->getHandler('Category');

if ($GLOBALS['myalbumModuleConfig']['htaccess']) {
    if (0 == $cid) {
        $url = XOOPS_URL . '/' . $GLOBALS['myalbumModuleConfig']['baseurl'] . '/cat,' . $cid . ',' . $uid . ',' . $num . ',' . $pos . ',' . $view . '.html';
    } else {
        $cat = $catHandler->get($cid);
        $url = $cat->getURL($uid, $num, $pos, $view);
    }

    if (!mb_strpos($url, $_SERVER['REQUEST_URI'])) {
        header('HTTP/1.1 301 Moved Permanently');
        header('Location: ' . $url);
        exit(0);
    }
}

// Orders
require_once $helper->path('include/photo_orders.php');
if (Request::hasVar('orderby', 'GET') && isset($myalbum_orders[$_GET['orderby']])) {
    $orderby = $_GET['orderby'];
} elseif (isset($myalbum_orders[$myalbum_defaultorder])) {
        $orderby = $myalbum_defaultorder;
    } else {
        //$orderby = 'titleA';
        $orderby = 'cidD';
    }


if ('table' === $view) {
    $GLOBALS['xoopsOption']['template_main'] = "{$moduleDirName }_viewcat_table.tpl";
    $function_assigning                      = 'XoopsModules\Myalbum\Preview::getArrayForPhotoAssignLight';
} else {
    $GLOBALS['xoopsOption']['template_main'] = "{$moduleDirName }_viewcat_list.tpl";
    $function_assigning                      = 'XoopsModules\Myalbum\Preview::getArrayForPhotoAssign';
}

require_once XOOPS_ROOT_PATH . '/header.php';

require_once __DIR__ . '/include/assign_globals.php';
foreach ($GLOBALS['myalbum_assign_globals'] as $key => $value) {
    $GLOBALS['xoopsTpl']->assign($key, $value);
}

if ($global_perms & GPERM_INSERTABLE) {
    $GLOBALS['xoopsTpl']->assign('lang_add_photo', _ALBM_ADDPHOTO);
}

$GLOBALS['xoopsTpl']->assign('lang_album_main', _ALBM_MAIN);

if ($cid > 0) {
    $cids    = [];
    $catpath = '';
    $cat     = $catHandler->get($cid);
    // Category Specified
    $GLOBALS['xoopsTpl']->assign('category_id', $cid);
    $GLOBALS['xoopsTpl']->assign('subcategories', Preview::getSubCategories($cid, $GLOBALS['cattree']));
    $GLOBALS['xoopsTpl']->assign('category_options', Utility::getCategoryOptions());

    foreach ($GLOBALS['cattree']->getAllChild($cid) as $child) {
        $cids[$child->getVar('cid')] = $child->getVar('cid');
    }
    $cids[]   = $cid;
    $criteria = new \CriteriaCompo(new \Criteria('status', '0', '>'));
    $photo_total_sum = Utility::getTotalCount($cids, $criteria);
    if (!empty($cids)) {
        foreach ($cids as $index => $child) {
            $childcat = $catHandler->get($child);
            if (is_object($childcat)) {
                $catpath .= "<a href='" . $helper->url() . 'viewcat.php?num=' . (int)$GLOBALS['myalbum_perpage'] . '&cid=' . $childcat->getVar('cid') . "' >" . $childcat->getVar('title') . '</a> ' . ($index < count($cids) ? '>>' : '');
            }
        }
    } else {
        $cat     = $catHandler->get($cid);
        $catpath .= "<a href='" . $helper->url() . 'viewcat.php?num=' . (int)$GLOBALS['myalbum_perpage'] . '&cid=' . $cat->getVar('cid') . "' >" . $cat->getVar('title') . '</a>';
    }
    $catpath   = str_replace('>>', " <span class='fg2'>&raquo;&raquo;</span> ", $catpath);
    $sub_title = preg_replace("/\'\>/", "'><img src='$mod_url/assets/images/folder16.gif' alt=''>", $catpath);
    $sub_title = preg_replace('/^(.+)folder16/', '$1folder_open', $sub_title);
    $GLOBALS['xoopsTpl']->assign('album_sub_title', $sub_title);
    $criteria->add(new \Criteria('cid', $cid));
} elseif (0 != $uid) {
    // This means 'my photo'
    if ($uid < 0) {
        $criteria = new \CriteriaCompo(new \Criteria('status', '0', '>'));
        $GLOBALS['xoopsTpl']->assign('uid', -1);
        $GLOBALS['xoopsTpl']->assign('album_sub_title', _ALBM_TEXT_SMNAME4);
        // uid Specified
    } else {
        $criteria = new \CriteriaCompo(new \Criteria('status', '0', '>'));
        $criteria->add(new \Criteria('submitter', $uid));
        $GLOBALS['xoopsTpl']->assign('uid', $uid);
        $GLOBALS['xoopsTpl']->assign('album_sub_title', "<img src='$mod_url/assets/images/myphotos.gif' alt='' >" . Preview::getNameFromUid($uid));
    }
} else {
    $criteria = new \CriteriaCompo(new \Criteria('status', '0', '>'));
    $GLOBALS['xoopsTpl']->assign('album_sub_title', 'error: category id not specified');
}

if (!isset($cat) || !is_object($cat)) {
    $cat = $catHandler->create();
}
$GLOBALS['xoopsTpl']->assign('rss', $cat->getRSSURL($uid, $num, $pos, $view));
$GLOBALS['xoopsTpl']->assign('xoConfig', $GLOBALS['myalbumModuleConfig']);
$GLOBALS['xoopsTpl']->assign('mydirname', $GLOBALS['mydirname']);

$photo_small_sum = $photosHandler->getCount($criteria);
$photo_total_sum = $photosHandler->getCount(new \Criteria('status', '0', '>'));
$GLOBALS['xoopsTpl']->assign('photo_small_sum', $photo_small_sum);
$GLOBALS['xoopsTpl']->assign('photo_total_sum', (empty($photo_total_sum) ? $photo_small_sum : $photo_total_sum));
$criteria->setOrder($myalbum_orders[$orderby][0]);
$criteria->setStart($pos);
$criteria->setSort($myalbum_orders[$orderby][0] . ', title');
$criteria->setLimit($num);

if ($photo_small_sum > 0) {
    //if 2 or more items in result, num the navigation menu
    if ($photo_small_sum > 1) {
        // Assign navigations like order and division
        $GLOBALS['xoopsTpl']->assign('show_nav', true);
        $GLOBALS['xoopsTpl']->assign('lang_sortby', _ALBM_SORTBY);
        $GLOBALS['xoopsTpl']->assign('lang_title', _ALBM_TITLE);
        $GLOBALS['xoopsTpl']->assign('lang_date', _ALBM_DATE);
        $GLOBALS['xoopsTpl']->assign('lang_rating', _ALBM_RATING);
        $GLOBALS['xoopsTpl']->assign('lang_popularity', _ALBM_POPULARITY);
        $GLOBALS['xoopsTpl']->assign('lang_cursortedby', sprintf(_ALBM_CURSORTEDBY, $myalbum_orders[$orderby][1]));
        //      if (!isset($get_append)) $get_append = 0;
        $nav      = new \XoopsPageNav($photo_small_sum, $num, $pos, 'pos', "num=$num&cid=$cid&orderby=$orderby");
        $nav_html = $nav->renderNav(10);

        $last = $pos + $num;
        if ($last > $photo_small_sum) {
            $last = $photo_small_sum;
        }
        $photonavinfo = sprintf(_ALBM_AM_PHOTONAVINFO, $pos + 1, $last, $photo_small_sum);
        $GLOBALS['xoopsTpl']->assign('photonav', $nav_html);
        $GLOBALS['xoopsTpl']->assign('photonavinfo', $photonavinfo);
    }
    // Display photos
    $count = 1;

    //    require_once __DIR__ . '/class/preview.php';

    foreach ($photosHandler->getObjects($criteria, true) as $lid => $photo) {
        //echo __LINE__.' - '.$function_assigning.' - '.$lid.'<br>';
        //        $photo = $function_assigning($photo) + array('count' => ++$count, true);
        $photo = $function_assigning($photo) + ['count' => ++$count, true];

        $GLOBALS['xoopsTpl']->append('photos', $photo);
    }
}

require_once XOOPS_ROOT_PATH . '/footer.php';
