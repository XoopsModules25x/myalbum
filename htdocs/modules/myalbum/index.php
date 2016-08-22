<?php
// ------------------------------------------------------------------------- //
//                      myAlbum-P - XOOPS photo album                        //
//                        <http://www.peak.ne.jp/>                           //
// ------------------------------------------------------------------------- //

include __DIR__ . '/header.php';

$catHandler    = xoops_getModuleHandler('cat', $GLOBALS['mydirname']);
$photosHandler = xoops_getModuleHandler('photos', $GLOBALS['mydirname']);

$num = empty($_GET['num']) ? $myalbum_newphotos : (int)$_GET['num'];
$pos = empty($_GET['pos']) ? 0 : (int)$_GET['pos'];

if ($GLOBALS['myalbumModuleConfig']['htaccess']) {
    $url = XOOPS_URL . '/' . $GLOBALS['myalbumModuleConfig']['baseurl'] . '/index,' . $num . ',' . $pos . $GLOBALS['myalbumModuleConfig']['endofurl'];
    if (!strpos($url, $_SERVER['REQUEST_URI'])) {
        header('HTTP/1.1 301 Moved Permanently');
        header('Location: ' . $url);
        exit;
    }
}

$GLOBALS['xoopsOption']['template_main'] = "{$moduleDirName }_index.tpl";
include $GLOBALS['xoops']->path('header.php');
// Modification apporté par black_beard alias MONTUY337513
/*if (!is_object($cat)) {
    $cat = $catHandler->create();
}*/
if (!isset($cat) || !is_object($cat)) {
    $cat = $catHandler->create();
}
// Fin de modification
$GLOBALS['xoopsTpl']->assign('rss', $cat->getRSSURL(0, $num, $pos, $myalbum_viewcattype));
$GLOBALS['xoopsTpl']->assign('xoConfig', $GLOBALS['myalbumModuleConfig']);
$GLOBALS['xoopsTpl']->assign('mydirname', $GLOBALS['mydirname']);

include __DIR__ . '/include/assign_globals.php';
foreach ($GLOBALS['myalbum_assign_globals'] as $key => $value) {
    $GLOBALS['xoopsTpl']->assign($key, $value);
}

$GLOBALS['xoopsTpl']->assign('subcategories', myalbum_get_sub_categories(0, $GLOBALS['cattree']));

$GLOBALS['xoopsTpl']->assign('category_options', myalbum_get_cat_options());

$criteria        = new Criteria('`status`', '0', '>');
$photo_num_total = $photosHandler->getCount($criteria);

$GLOBALS['xoopsTpl']->assign('photo_global_sum', sprintf(_ALBM_THEREARE, $photo_num_total));
if ($global_perms & GPERM_INSERTABLE) {
    $GLOBALS['xoopsTpl']->assign('lang_add_photo', _ALBM_ADDPHOTO);
}

// Navigation

if ($num < 1) {
    $num = $myalbum_newphotos;
}
if ($pos >= $photo_num_total) {
    $pos = 0;
}
if ($photo_num_total > $num) {
    $nav      = new XoopsPageNav($photo_num_total, $num, $pos, 'pos', "num=$num");
    $nav_html = $nav->renderNav(10);
    $last     = $pos + $num;
    if ($last > $photo_num_total) {
        $last = $photo_num_total;
    }
    $photonavinfo = sprintf(_ALBM_AM_PHOTONAVINFO, $pos + 1, $last, $photo_num_total);
    $GLOBALS['xoopsTpl']->assign('photonavdisp', true);
    $GLOBALS['xoopsTpl']->assign('photonav', $nav_html);
    $GLOBALS['xoopsTpl']->assign('photonavinfo', $photonavinfo);
} else {
    $GLOBALS['xoopsTpl']->assign('photonavdisp', false);
}

$criteria = new Criteria('`status`', '0', '>');
$criteria->setStart($pos);
$criteria->setLimit($num);
//$criteria->setSort('`date`');
$criteria->setSort('`cid`');
$criteria->setOrder('DESC');
// Assign Latest Photos
foreach ($photosHandler->getObjects($criteria, true) as $lid => $photo) {
    $GLOBALS['xoopsTpl']->append_by_ref('photos', myalbum_get_array_for_photo_assign($photo, true));
}

include $GLOBALS['xoops']->path('footer.php');
