<?php
// ------------------------------------------------------------------------- //
//                      myAlbum-P - XOOPS photo album                        //
//                        <http://www.peak.ne.jp>                           //
// ------------------------------------------------------------------------- //

require_once __DIR__ . '/admin_header.php';

// GET vars
$pos = \Xmf\Request::getInt('pos', 0, 'GET');
$num = \Xmf\Request::getInt('num', 10, 'GET');
$txt = empty($_GET['txt']) ? '' : $GLOBALS['myts']->stripSlashesGPC(trim($_GET['txt']));

if (!empty($_POST['action']) && 'admit' === $_POST['action'] && isset($_POST['ids']) && is_array($_POST['ids'])) {
    /** @var MyalbumPhotosHandler $photosHandler */
    $photosHandler = $helper->getHandler('Photos');
    @$photosHandler->setStatus($_POST['ids'], 1);
    redirect_header('admission.php', 2, _ALBM_AM_ADMITTING);
} elseif (!empty($_POST['action']) && 'delete' === $_POST['action'] && isset($_POST['ids']) && is_array($_POST['ids'])) {
    // remove records

    // Double check for anti-CSRF
    if (!XoopsSecurity::checkReferer()) {
        exit('XOOPS_URL is not included in your REFERER');
    }
    /** @var MyalbumPhotosHandler $photosHandler */
    $photosHandler = $helper->getHandler('Photos');
    @$photosHandler->deletePhotos($_POST['ids']);

    redirect_header('admission.php', 2, _ALBM_DELETINGPHOTO);
}
/** @var MyalbumPhotosHandler $photosHandler */
$photosHandler = $helper->getHandler('Photos');

// extracting by free word
$criteria = new \CriteriaCompo(new \Criteria('`status`', '0', '<='));
if ('' !== $txt) {
    $keywords = explode(' ', $txt);
    foreach ($keywords as $keyword) {
        $criteria->add(new \Criteria('CONCAT( l.title , l.ext )', '%' . $keyword . '%', 'LIKE'), 'AND');
    }
}
xoops_cp_header();
$adminObject = \Xmf\Module\Admin::getInstance();
$adminObject->displayNavigation(basename(__FILE__));
//myalbum_adminMenu(basename(__FILE__), 3);
$GLOBALS['xoopsTpl']->assign('admin_title', sprintf(_AM_H3_FMT_ADMISSION, $xoopsModule->name()));
$GLOBALS['xoopsTpl']->assign('mydirname', $GLOBALS['mydirname']);
$GLOBALS['xoopsTpl']->assign('photos_url', $GLOBALS['photos_url']);
$GLOBALS['xoopsTpl']->assign('thumbs_url', $GLOBALS['thumbs_url']);
$GLOBALS['xoopsTpl']->assign('txt', $txt);
$GLOBALS['xoopsTpl']->assign('num', $num);
$GLOBALS['xoopsTpl']->assign('pos', $pos);

// query for listing count
$numrows = $photosHandler->getCount($criteria);

// Page Navigation
$nav      = new \XoopsPageNav($numrows, $num, $pos, 'pos', "num=$num&txt=" . urlencode($txt));
$nav_html = $nav->renderNav(10);
$GLOBALS['xoopsTpl']->assign('nav_html', $nav_html);

$criteria = new \Criteria('`status`', '0', '<=');
$criteria->setStart($pos);
$criteria->setLimit($num);

foreach ($photosHandler->getObjects($criteria, true) as $lid => $photo) {
    $GLOBALS['xoopsTpl']->append('photos', $photo->toArray());
}

$GLOBALS['xoopsTpl']->display('db:' . $GLOBALS['mydirname'] . '_cpanel_admission.tpl');

// check $xoopsModule
//  myalbum_footer_adminMenu();
require_once __DIR__ . '/admin_footer.php';
