<?php

// for older files
function myalbum_header()
{
    global $mod_url, $mydirname;

    $tpl = new XoopsTpl();
    $tpl->assign(array('mod_url' => $mod_url));
    $tpl->display("db:{$mydirname}_header.html");
}

// for older files
function myalbum_footer()
{
    global $mod_copyright, $mydirname;

    $tpl = new XoopsTpl();
    $tpl->assign(array('mod_copyright' => $mod_copyright));
    $tpl->display("db:{$mydirname}_footer.html");
}

// returns appropriate name from uid
function myalbum_get_name_from_uid($uid)
{
    global $myalbum_nameoruname;

    if ($uid > 0) {
        $member_handler =& xoops_getHandler('member');
        $poster         =& $member_handler->getUser($uid);

        if (is_object($poster)) {
            if ($myalbum_nameoruname === 'uname') {
                $name = $poster->uname();
            } else {
                $name = htmlspecialchars($poster->name());
                if ($name == '') {
                    $name = $poster->uname();
                }
            }
        } else {
            $name = _ALBM_CAPTION_GUESTNAME;
        }

    } else {
        $name = _ALBM_CAPTION_GUESTNAME;
    }

    return $name;
}

// Get photo's array to assign into template (heavy version)
function myalbum_get_array_for_photo_assign($photo, $summary = false)
{
    global $my_uid, $isadmin, $global_perms;
    global $photos_url, $thumbs_url, $thumbs_dir, $mod_url, $mod_path;
    global $myalbum_makethumb, $myalbum_thumbsize, $myalbum_popular, $myalbum_newdays, $myalbum_normal_exts;

    $photos_handler   = xoops_getModuleHandler('photos', $GLOBALS['mydirname']);
    $text_handler     = xoops_getModuleHandler('text', $GLOBALS['mydirname']);
    $cat_handler      = xoops_getModuleHandler('cat', $GLOBALS['mydirname']);
    $votedata_handler = xoops_getModuleHandler('votedata', $GLOBALS['mydirname']);
    $comments_handler = xoops_getModuleHandler('comments', $GLOBALS['mydirname']);

    extract($photo->toArray(true));
    $text = $text_handler->get($photo->getVar('lid'));
    $cat  = $cat_handler->get($photo->getVar('cid'));
    $ext  = $photo->vars['ext']['value'];

    if (in_array(strtolower($ext), $myalbum_normal_exts)) {
        $imgsrc_thumb    = $photo->getThumbsURL();
        $imgsrc_photo    = $photo->getPhotoURL();
        $ahref_photo     = $photo->getPhotoURL();
        $is_normal_image = true;

        // Width of thumb
        $width_spec = "width='$myalbum_thumbsize'";
        if ($myalbum_makethumb) {
            list($width, $height, $type) = getimagesize("$thumbs_dir/$lid.$ext");
            // if thumb images was made, 'width' and 'height' will not set.
            if ($width <= $myalbum_thumbsize) {
                $width_spec = '';
            }
        }
    } else {

        $imgsrc_thumb    = $photo->getThumbsURL();
        $imgsrc_photo    = $photo->getPhotoURL();
        $ahref_photo     = $photo->getPhotoURL();
        $is_normal_image = false;
        $width_spec      = '';
    }

    // Voting stats
    if ($rating > 0) {

        if ($votes == 1) {
            $votestring = _ALBM_ONEVOTE;
        } else {
            $votestring = sprintf(_ALBM_NUMVOTES, $votes);
        }
        $info_votes = number_format($rating, 2) . " ($votestring)";
    } else {
        $info_votes = '0.00 (' . sprintf(_ALBM_NUMVOTES, 0) . ')';
    }

    // Submitter's name
    $submitter_name = myalbum_get_name_from_uid($submitter);

    // Category's title
    $cat_title = !is_object($cat) ? '' : $cat->getVar('title');

    // Summarize description
    if (is_object($text)) {
        if ($summary) {
            $description = extractSummary($text->getVar('description'));
        } else {
            $description = $text->getVar('description');
        }
    } else {
        $description = '';
    }

    if (!empty($_POST['preview'])) {
        $description = $GLOBALS['myts']->stripSlashesGPC($_POST['desc_text']);
        $title       = $GLOBALS['myts']->stripSlashesGPC($_POST['title']);
    }

    if ($GLOBALS['myalbumModuleConfig']['tag']) {
        include_once XOOPS_ROOT_PATH . '/modules/tag/include/tagbar.php';
        $tagbar = tagBar($lid, $cid);
    } else {
        $tagbar = array();
    }

    return array(
        'tagbar'          => $tagbar,
        'lid'             => $lid,
        'cid'             => $cid,
        'ext'             => $ext,
        'res_x'           => $res_x,
        'res_y'           => $res_y,
        'window_x'        => $res_x + 16,
        'window_y'        => $res_y + 16,
        'title'           => $GLOBALS['myts']->htmlSpecialChars($title),
        'datetime'        => formatTimestamp($date, 'm'),
        'description'     => $GLOBALS['myts']->displayTarea($description, 1, 1, 1, 1, 1, 1),
        'imgsrc_thumb'    => $imgsrc_thumb,
        'imgsrc_photo'    => $imgsrc_photo,
        'ahref_photo'     => $ahref_photo,
        'width_spec'      => $width_spec,
        'can_edit'        => ($global_perms & GPERM_EDITABLE) && ($my_uid == $submitter || $isadmin),
        'can_delete'      => ($global_perms & GPERM_DELETABLE) && ($my_uid == $submitter || $isadmin),
        'submitter'       => $submitter,
        'submitter_name'  => $submitter_name,
        'hits'            => $hits,
        'rating'          => $rating,
        'rank'            => floor($rating - 0.001),
        'votes'           => $votes,
        'info_votes'      => $info_votes,
        'comments'        => $comments,
        'is_normal_image' => $is_normal_image,
        'is_newphoto'     => $date > time() - 86400 * $myalbum_newdays && $status == 1,
        'is_updatedphoto' => $date > time() - 86400 * $myalbum_newdays && $status == 2,
        'is_popularphoto' => $hits >= $myalbum_popular,
        'info_morephotos' => sprintf(_ALBM_MOREPHOTOS, $submitter_name),
        'cat_title'       => $GLOBALS['myts']->htmlSpecialChars($cat_title),
        'status'          => $status
    );
}

// Get photo's array to assign into template (light version)
function myalbum_get_array_for_photo_assign_light($photo, $summary = false)
{
    global $my_uid, $isadmin, $global_perms;
    global $photos_url, $thumbs_url, $thumbs_dir;
    global $myalbum_makethumb, $myalbum_thumbsize, $myalbum_normal_exts;

    $photos_handler   = xoops_getModuleHandler('photos', $GLOBALS['mydirname']);
    $text_handler     = xoops_getModuleHandler('text', $GLOBALS['mydirname']);
    $cat_handler      = xoops_getModuleHandler('cat', $GLOBALS['mydirname']);
    $votedata_handler = xoops_getModuleHandler('votedata', $GLOBALS['mydirname']);
    $comments_handler = xoops_getModuleHandler('comments', $GLOBALS['mydirname']);

    extract($photo->toArray(true));
    $text = $text_handler->get($photo->getVar('lid'));
    $cat  = $cat_handler->get($photo->getVar('cid'));

    if (in_array(strtolower($ext), $myalbum_normal_exts)) {
        $imgsrc_thumb    = $photo->getThumbsURL();
        $imgsrc_photo    = $photo->getPhotoURL();
        $is_normal_image = true;
        // Width of thumb
        $width_spec = "width='$myalbum_thumbsize'";
        if ($myalbum_makethumb && $ext !== 'gif') {
            // if thumb images was made, 'width' and 'height' will not set.
            $width_spec = '';
        }
    } else {
        $imgsrc_thumb    = $photo->getThumbsURL();
        $imgsrc_photo    = $photo->getPhotoURL();
        $is_normal_image = false;
        $width_spec      = '';
    }

    if ($GLOBALS['myalbumModuleConfig']['tag']) {
        include_once XOOPS_ROOT_PATH . '/modules/tag/include/tagbar.php';
        $tagbar = tagBar($lid, $cid);
    } else {
        $tagbar = array();
    }

    return array(
        'tagbar'          => $tagbar,
        'lid'             => $lid,
        'cid'             => $cid,
        'ext'             => $ext,
        'res_x'           => $res_x,
        'res_y'           => $res_y,
        'window_x'        => $res_x + 16,
        'window_y'        => $res_y + 16,
        'title'           => $GLOBALS['myts']->htmlSpecialChars($title),
        'imgsrc_thumb'    => $imgsrc_thumb,
        'imgsrc_photo'    => $imgsrc_photo,
        'width_spec'      => $width_spec,
        'can_edit'        => ($global_perms & GPERM_EDITABLE) && ($my_uid == $submitter || $isadmin),
        'can_delete'      => ($global_perms & GPERM_DELETABLE) && ($my_uid == $submitter || $isadmin),
        'hits'            => $hits,
        'rating'          => $rating,
        'rank'            => floor($rating - 0.001),
        'votes'           => $votes,
        'comments'        => $comments,
        'is_normal_image' => $is_normal_image
    );
}

// get list of sub categories in header space
function myalbum_get_sub_categories($parent_id, $cattree)
{

    $ret      = array();
    $criteria = new Criteria('`status`', '0', '>');
    $criterib = new Criteria('`pid`', $parent_id, '=');

    $cat_handler = xoops_getModuleHandler('cat', $GLOBALS['mydirname']);

    $cats = $cat_handler->getObjects($criterib, true);

    foreach ($cats as $cid => $cat) {
        extract($cat->toArray());
        // Show first child of this category
        $subcat = array();
        $arr    = $GLOBALS['cattree']->getFirstChild($cid);
        foreach ($arr as $child) {
            $subcat[] = array(
                'cid'              => $child->getVar('cid'),
                'title'            => $child->getVar('title'),
                'photo_small_sum'  => myalbum_get_photo_small_sum_from_cat($child->getVar('cid'), $criteria),
                'number_of_subcat' => count($GLOBALS['cattree']->getFirstChild($child->getVar('cid')))
            );
        }

        // Category's banner default
        if ($imgurl === 'http://') {
            $imgurl = '';
        }

        // Total sum of photos
        $cids = array();
        foreach ($GLOBALS['cattree']->getAllChild($cid, array()) as $children) {
            $cids[] = $children->getVar('cid');
        }

        array_push($cids, $cid);

        $photo_total_sum = myalbum_get_photo_total_sum_from_cats($cids, $criteria);

        $ret[] = array(
            'cid'             => $cid,
            'imgurl'          => $GLOBALS['myts']->htmlSpecialChars($imgurl),
            'photo_small_sum' => myalbum_get_photo_small_sum_from_cat($cid, $criteria),
            'photo_total_sum' => $photo_total_sum,
            'title'           => $title,
            'subcategories'   => $subcat
        );
    }

    return $ret;
}

// get attributes of <img> for preview image
function myalbum_get_img_attribs_for_preview($preview_name)
{
    global $photos_url, $mod_url, $mod_path, $myalbum_normal_exts, $myalbum_thumbsize;

    $ext = substr(strrchr($preview_name, '.'), 1);

    if (in_array(strtolower($ext), $myalbum_normal_exts)) {
        return array("$photos_url/$preview_name", "width='$myalbum_thumbsize'", "$photos_url/$preview_name");

    } else {
        if (file_exists("$mod_path/icons/$ext.gif")) {
            return array("$mod_url/icons/mp3.gif", '', "$photos_url/$preview_name");
        } else {
            return array("$mod_url/icons/default.gif", '', "$photos_url/$preview_name");
        }
    }
}
