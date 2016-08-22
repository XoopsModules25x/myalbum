<?php
// ------------------------------------------------------------------------- //
//                      myAlbum-P - XOOPS photo album                        //
//                        <http://www.peak.ne.jp/>                           //
// ------------------------------------------------------------------------- //

include __DIR__ . '/header.php';

$hit  = (isset($_GET['hit']) ? (int)$_GET['hit'] : 0);
$rate = (isset($_GET['rate']) ? (int)$_GET['rate'] : 0);

if ($GLOBALS['myalbumModuleConfig']['htaccess']) {
    $url = XOOPS_URL . '/' . $GLOBALS['myalbumModuleConfig']['baseurl'] . '/top,' . $hit . ',' . $rate . '.html';
    if (!strpos($url, $_SERVER['REQUEST_URI'])) {
        header('HTTP/1.1 301 Moved Permanently');
        header('Location: ' . $url);
        exit(0);
    }
}

$photosHandler = xoops_getModuleHandler('photos', $GLOBALS['mydirname']);
$catHandler    = xoops_getModuleHandler('cat', $GLOBALS['mydirname']);

$GLOBALS['xoopsOption']['template_main'] = "{$moduleDirName }_topten.tpl";

include XOOPS_ROOT_PATH . '/header.php';

include __DIR__ . '/include/assign_globals.php';
$GLOBALS['xoopsTpl']->assign($myalbum_assign_globals);
$GLOBALS['xoopsTpl']->assign('xoConfig', $GLOBALS['myalbumModuleConfig']);
$GLOBALS['xoopsTpl']->assign('mydirname', $GLOBALS['mydirname']);

//generates top 10 charts by rating and hits for each main category
if ($rate == 1) {
    $lang_sortby = _ALBM_RATING;
    unset($_GET['hit']);
} else {
    $lang_sortby = _ALBM_HITS;
    unset($_GET['rate']);
}

$GLOBALS['xoopsTpl']->assign('lang_sortby', $lang_sortby);
$GLOBALS['xoopsTpl']->assign('lang_rank', _ALBM_RANK);
$GLOBALS['xoopsTpl']->assign('lang_title', _ALBM_TITLE);
$GLOBALS['xoopsTpl']->assign('lang_category', _ALBM_CATEGORY);
$GLOBALS['xoopsTpl']->assign('lang_hits', _ALBM_HITS);
$GLOBALS['xoopsTpl']->assign('lang_rating', _ALBM_RATING);
$GLOBALS['xoopsTpl']->assign('lang_vote', _ALBM_VOTE);

$criteria = new Criteria('`pid`', '0');
$criteria->setOrder('`title`');
$rankings = array();
$i        = 0;
foreach ($catHandler->getObjects($criteria, true) as $cid => $cat) {
    if ($rate == 1) {
        $rankings[$i] = array(
            'title' => sprintf(_ALBM_TOP10, $GLOBALS['myts']->htmlSpecialChars($cat->getVar('title'))),
            'count' => $i
        );
    } else {
        $rankings[$i] = array(
            'title' => sprintf(_ALBM_POPULARTOP10, $GLOBALS['myts']->htmlSpecialChars($cat->getVar('title'))),
            'count' => $i
        );
    }

    $whr_cid = array($cid => $cid);
    // get all child cat ids for a given cat id
    foreach ($GLOBALS['cattree']->getAllChild($cid) as $children) {
        $whr_cid[$children->getVar('cid')] = $children->getVar('cid');
    }
    $criteria = new CriteriaCompo(new Criteria('`status`', '0', '>'));
    $criteria->add(new Criteria('`cid`', '(' . implode(',', $whr_cid) . ')', 'IN'));
    if ($rate == 1) {
        $criteria->setSort('rating');
        $criteria->setOrder('DESC');
    } else {
        $criteria->setSort('hits');
        $criteria->setOrder('DESC');
    }
    $criteria->setStart(0);
    $criteria->setLimit(10);

    $rank = 1;
    foreach ($photosHandler->getObjects($criteria, true) as $lid => $photo) {
        $catpath = '';

        $cids = $GLOBALS['cattree']->getAllChild($photo->getVar('cid'));

        if (!empty($cids)) {
            foreach ($cids as $index => $child) {
                $catpath .= "<a href='" . XOOPS_URL . '/modules/' . $GLOBALS['mydirname'] . '/viewcat.php?num=' . (int)$GLOBALS['myalbum_perpage'] . '&cid=' . $child->getVar('cid') . "' >" . $child->getVar('title') . '</a> ' . ($index
                                                                                                                                                                                                                                    < count($cids) ? '>>' : '');
            }
        } else {
            $cat = $catHandler->get($photo->getVar('cid'));
            $catpath .= "<a href='" . XOOPS_URL . '/modules/' . $GLOBALS['mydirname'] . '/viewcat.php?num=' . (int)$GLOBALS['myalbum_perpage'] . '&cid=' . $cat->getVar('cid') . "' >" . $cat->getVar('title') . '</a>';
        }

        $catpath = str_replace('>>', " <span class='fg2'>&raquo;&raquo;</span> ", $catpath);

        $rankings[$i]['photo'][] = array(
            'lid'            => $lid,
            'cid'            => $photo->getVar('cid'),
            'rank'           => $rank,
            'title'          => $photo->getVar('title'),
            'submitter'      => $photo->getVar('submitter'),
            'submitter_name' => myalbum_get_name_from_uid($photo->getVar('submitter')),
            'category'       => $catpath,
            'hits'           => $photo->getVar('hits'),
            'rating'         => number_format($photo->getVar('rating'), 2),
            'votes'          => $photo->getVar('votes')
        );

        ++$rank;
    }

    ++$i;
}

$GLOBALS['xoopsTpl']->assign_by_ref('rankings', $rankings);

include XOOPS_ROOT_PATH . '/footer.php';
