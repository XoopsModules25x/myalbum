<?php
// ------------------------------------------------------------------------- //
//                      myAlbum-P - XOOPS photo album                        //
//                        <http://www.peak.ne.jp>                           //
// ------------------------------------------------------------------------- //
use Xmf\Request;
use XoopsModules\Myalbum\{
    CategoryHandler,
    Helper,
    PhotosHandler,
    Preview,
    Utility
};
/** @var Helper $helper */
/** @var CategoryHandler $catHandler */
/** @var PhotosHandler $photosHandler */

$catpath = '';
require_once __DIR__ . '/header.php';

// GET variables
$lid = Request::getInt('lid', 0, 'GET');
$cid = Request::getInt('cid', 0, 'GET');

if (Request::hasVar('op', 'GET')) {
    $op = $_GET['op'];
} else {
    $op = 'default';
}

// Switch operations

/**
 * @param $lid
 * @param $cid
 */
function deleteImage($lid, $cid)
{
    global $global_perms;
    $helper = Helper::getInstance();
    $photosHandler = $helper->getHandler('Photos');
    $photo_obj     = $photosHandler->get($lid);

    if (!($global_perms & GPERM_DELETABLE)) {
        redirect_header('photo.php', 3, _NOPERM);
    }

    // anti-CSRF
    $xsecurity = new XoopsSecurity();
    if (!$xsecurity->checkReferer()) {
        exit('XOOPS_URL is not included in your REFERER');
    }

    // get and check lid is valid
    if ($lid < 1) {
        exit('Invalid photo id.');
    }

    $photosHandler->delete($photo_obj);

    if ($cid > 0) {
        redirect_header('viewcat.php?cid=' . $cid, 2, _ALBM_DBUPDATED);
    } else {
        redirect_header('index.php', 2, _ALBM_DBUPDATED);
    }
}

switch ($op) {
    case 'delete':
        deleteImage($lid, $cid);
        break;
    case 'default':
    default:
        Utility::updateRating($lid);
        $photosHandler = $helper->getHandler('Photos');
        $catHandler = $helper->getHandler('Category');

        if (!is_object($photo_obj = $photosHandler->get($lid))) {
            redirect_header('index.php', 2, _ALBM_NOMATCH);
        }

        if (!mb_strpos($photo_obj->getURL(), $_SERVER['REQUEST_URI'])) {
            header('HTTP/1.1 301 Moved Permanently');
            header('Location: ' . $photo_obj->getURL());
            exit(0);
        }

        $cat = $catHandler->get($photo_obj->getVar('cid'));

        $GLOBALS['xoopsOption']['template_main'] = "{$moduleDirName }_photo.tpl";
        require $GLOBALS['xoops']->path('header.php');

        if ($global_perms & GPERM_INSERTABLE) {
            $GLOBALS['xoopsTpl']->assign('lang_add_photo', _ALBM_ADDPHOTO);
        }
        $GLOBALS['xoopsTpl']->assign('lang_album_main', _ALBM_MAIN);
        require_once __DIR__ . '/include/assign_globals.php';
        foreach ($GLOBALS['myalbum_assign_globals'] as $key => $value) {
            $GLOBALS['xoopsTpl']->assign($key, $value);
        }

        if ($photo_obj->getVar('status') < 1) {
            redirect_header($mod_url, 3, _ALBM_NOMATCH);
        }

        // update hit count
        $photo_obj->increaseHits(1);

        $photo = Preview::getArrayForPhotoAssign($photo_obj);

        // Middle size calculation
        $photo['width_height'] = '';
        $res_x                 = $photo_obj->getVar('res_x');
        $res_y                 = $photo_obj->getVar('res_y');
        [$max_w, $max_h] = explode('x', $myalbum_middlepixel);
        //if ( ! empty( $max_w ) && ! empty( $p['res_x'] ) ) {
        if (!empty($max_w) && !empty($res_x)) {
            if (empty($max_h)) {
                $max_h = $max_w;
            }
            if ($max_h / $max_w > $res_y / $res_x) {
                if ($res_x > $max_w) {
                    $photo['width_height'] = "width='$max_w'";
                }
            } elseif ($res_y > $max_h) {
                    $photo['width_height'] = "height='$max_h'";
                }

        }

        $GLOBALS['xoopsTpl']->assign_by_ref('photo', $photo);

        // Category Information

        $GLOBALS['xoopsTpl']->assign('category_id', $cid);
        $cids = $GLOBALS['cattree']->getAllChild($cid);
        if (!empty($cids)) {
            foreach ($cids as $index => $child) {
                $catpath .= "<a href='" . $helper->url() . 'viewcat.php?num=' . (int)$GLOBALS['myalbum_perpage'] . '&cid=' . $child->getVar('cid') . "' >" . $child->getVar('title') . '</a> ' . ($index < count($cids) ? '>>' : '');
            }
        } else {
            $cat     = $catHandler->get($photo_obj->getVar('cid'));
            $catpath .= "<a href='" . $helper->url() . 'viewcat.php?num=' . (int)$GLOBALS['myalbum_perpage'] . '&cid=' . $cat->getVar('cid') . "' >" . $cat->getVar('title') . '</a>';
        }
        $catpath   = str_replace('>>', " <span class='fg2'>&raquo;&raquo;</span> ", $catpath);
        $sub_title = preg_replace("/\'\>/", "'><img src='$mod_url/assets/images/folder16.gif' alt=''>", $catpath);
        $sub_title = preg_replace('/^(.+)folder16/', '$1folder_open', $sub_title);
        $GLOBALS['xoopsTpl']->assign('album_sub_title', $sub_title);

        // Orders
        require_once $helper->path('include/photo_orders.php');
        if (Request::hasVar('orderby', 'GET') && isset($myalbum_orders[$_GET['orderby']])) {
            $orderby = $_GET['orderby'];
        } elseif (isset($myalbum_orders[$myalbum_defaultorder])) {
                $orderby = $myalbum_defaultorder;
            } else {
                $orderby = 'lidA';
            }


        $criteria = new \CriteriaCompo(new \Criteria('status', '0', '>'));
        $criteria->add(new \Criteria('cid', $photo_obj->getVar('cid')));
        $criteria->setOrder($myalbum_orders[$orderby][0]);
        // create category navigation
        $ids = [];
        foreach ($photosHandler->getObjects($criteria, true) as $id => $pht) {
            $ids[] = $id;
        }

        $photo_nav = '';
        $numrows   = count($ids);
        $pos       = array_search($lid, $ids, true);
        if ($numrows > 1) {
            // prev mark
            if ($ids[0] != $lid) {
                $photo_nav .= "<a href='photo.php?lid=" . $ids[0] . "'><b>[&lt; </b></a>&nbsp;&nbsp;";
                $photo_nav .= "<a href='photo.php?lid=" . $ids[$pos - 1] . "'><b>" . _ALBM_PREVIOUS . '</b></a>&nbsp;&nbsp;';
            }

            $nwin = 7;
            if ($numrows > $nwin) { // window
                if ($pos > $nwin / 2) {
                    if ($pos > round($numrows - ($nwin / 2) - 1)) {
                        $start = $numrows - $nwin + 1;
                    } else {
                        $start = round($pos - ($nwin / 2)) + 1;
                    }
                } else {
                    $start = 1;
                }
            } else {
                $start = 1;
            }

            for ($i = $start; $i < $numrows + 1 && $i < $start + $nwin; ++$i) {
                if ($ids[$i - 1] == $lid) {
                    $photo_nav .= "$i&nbsp;&nbsp;";
                } else {
                    $photo_nav .= "<a href='photo.php?lid=" . $ids[$i - 1] . "'>$i</a>&nbsp;&nbsp;";
                }
            }

            // next mark
            if ($ids[$numrows - 1] != $lid) {
                $photo_nav .= "<a href='photo.php?lid=" . $ids[$pos + 1] . "'><b>" . _ALBM_NEXT . '</b></a>&nbsp;&nbsp;';
                $photo_nav .= "<a href='photo.php?lid=" . $ids[$numrows - 1] . "'><b> >]</b></a>";
            }
        }

        $GLOBALS['xoopsTpl']->assign('photo_nav', $photo_nav);
        $GLOBALS['xoopsTpl']->assign('xoops_pagetitle', $photo_obj->getVar('title') . ' : ' . $cat->getVar('title') . ' : ' . $GLOBALS['xoopsModule']->getVar('name'));

        // comments

        require_once XOOPS_ROOT_PATH . '/include/comment_view.php';

        require $GLOBALS['xoops']->path('footer.php');
}
