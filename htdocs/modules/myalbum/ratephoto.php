<?php
// ------------------------------------------------------------------------- //
//                      myAlbum-P - XOOPS photo album                        //
//                        <http://www.peak.ne.jp/>                           //
// ------------------------------------------------------------------------- //
include 'header.php';

if (!($global_perms & GPERM_RATEVOTE)) {
    redirect_header(XOOPS_URL . '/modules/' . $GLOBALS['mydirname'] . '/index.php', 1, _NOPERM);
    exit;
}

$lid = empty($_GET['lid']) ? 0 : (int)$_GET['lid'];

$photos_handler   =& xoops_getModuleHandler('photos', $GLOBALS['mydirname']);
$votedata_handler =& xoops_getModuleHandler('votedata', $GLOBALS['mydirname']);
if (!$photo_obj = $photos_handler->get($lid)) {
    redirect_header(XOOPS_URL . '/modules/' . $GLOBALS['mydirname'] . '/index.php', 2, _ALBM_NOMATCH);
    exit;
}

if (isset($_POST['submit'])) {
    $ratinguser = $my_uid;

    //Make sure only 1 anonymous from an IP in a single day.
    $anonwaitdays = 1;
    $ip           = getenv('REMOTE_ADDR');
    $lid          = (int)$_POST['lid'];
    $rating       = (int)$_POST['rating'];
    // Check if rating is valid
    if ($rating <= 0 || $rating > 10) {
        redirect_header($photo_obj->getRateURL(), 4, _ALBM_NORATING);
        exit;
    }

    if ($ratinguser !== 0) {

        // Check if Photo POSTER is voting
        $criteria = new CriteriaCompo(new Criteria('`lid`', $lid, '='));
        $criteria->add(new Criteria('`submitter`', $ratinguser));

        if ($photos_handler->getCount($criteria)) {
            redirect_header(XOOPS_URL . '/modules/' . $GLOBALS['mydirname'] . '/index.php', 4, _ALBM_CANTVOTEOWN);
            exit;
        }

        $criteria = new CriteriaCompo(new Criteria('`lid`', $lid, '='));
        $criteria->add(new Criteria('`ratinguser`', $ratinguser));

        // Check if REG user is trying to vote twice.
        if ($votedata_handler->getCount($criteria)) {
            redirect_header(XOOPS_URL . '/modules/' . $GLOBALS['mydirname'] . '/index.php', 4, _ALBM_VOTEONCE2);
            exit;
        }
    } else {
        // Check if ANONYMOUS user is trying to vote more than once per day.
        $yesterday = (time() - (86400 * $anonwaitdays));
        $criteria  = new CriteriaCompo(new Criteria('`ratingtimestamp`', $yesterday, '>'));
        $criteria->add(new Criteria('`ratinguser`', 0));
        $criteria->add(new Criteria('`ratinghostname`', $ip));
        // Check if REG user is trying to vote twice.
        if ($votedata_handler->getCount($criteria)) {
            redirect_header(XOOPS_URL . '/modules/' . $GLOBALS['mydirname'] . '/index.php', 4, _ALBM_VOTEONCE2);
            exit;
        }
    }

    // All is well.  Add to Line Item Rate to DB.
    $vote     = $votedata_handler->create();
    $datetime = time();
    $vote->setVar('lid', $lid);
    $vote->setVar('ratinguser', $ratinguser);
    $vote->setVar('rating', $rating);
    $vote->setVar('ratinghostname', $ip);
    $vote->setVar('ratingtimestamp', $datetime);
    $votedata_handler->insert($vote, true) or die('DB error: INSERT votedata table');
    //All is well.  Calculate Score & Add to Summary (for quick retrieval & sorting) to DB.
    myalbum_updaterating($lid);
    $ratemessage = _ALBM_VOTEAPPRE . '<br />' . sprintf(_ALBM_THANKURATE, $xoopsConfig['sitename']);
    redirect_header(XOOPS_URL . '/modules/' . $GLOBALS['mydirname'] . '/index.php', 2, $ratemessage);
    exit;
} else {
    if (!strpos($photo_obj->getRateURL(), $_SERVER['REQUEST_URI'])) {
        header('HTTP/1.1 301 Moved Permanently');
        header('Location: ' . $photo_obj->getRateURL());
        exit(0);
    }

    $xoopsOption['template_main'] = "{$mydirname}_ratephoto.tpl";
    include $GLOBALS['xoops']->path('header.php');

    $GLOBALS['xoopsTpl']->assign('photo', myalbum_get_array_for_photo_assign($photo_obj));

    include 'include/assign_globals.php';
    $GLOBALS['xoopsTpl']->assign($myalbum_assign_globals);

    $GLOBALS['xoopsTpl']->assign('lang_voteonce', _ALBM_VOTEONCE);
    $GLOBALS['xoopsTpl']->assign('lang_ratingscale', _ALBM_RATINGSCALE);
    $GLOBALS['xoopsTpl']->assign('lang_beobjective', _ALBM_BEOBJECTIVE);
    $GLOBALS['xoopsTpl']->assign('lang_donotvote', _ALBM_DONOTVOTE);
    $GLOBALS['xoopsTpl']->assign('lang_rateit', _ALBM_RATEIT);
    $GLOBALS['xoopsTpl']->assign('lang_cancel', _CANCEL);
    $GLOBALS['xoopsTpl']->assign('xoConfig', $GLOBALS['myalbumModuleConfig']);
    $GLOBALS['xoopsTpl']->assign('mydirname', $GLOBALS['mydirname']);

    include $GLOBALS['xoops']->path('footer.php');
}
