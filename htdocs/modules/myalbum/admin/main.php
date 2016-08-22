<?php
// ------------------------------------------------------------------------- //
//                      myAlbum-P - XOOPS photo album                        //
//                        <http://www.peak.ne.jp/>                           //
// ------------------------------------------------------------------------- //

include_once __DIR__ . '/admin_header.php';

$catHandler    = xoops_getModuleHandler('cat', $GLOBALS['mydirname']);
$photosHandler = xoops_getModuleHandler('photos', $GLOBALS['mydirname']);
global $pathIcon16;

// GPCS vars
$action = isset($_POST['action']) ? $_POST['action'] : '';
$disp   = isset($_GET['disp']) ? $_GET['disp'] : '';
$cid    = isset($_GET['cid']) ? (int)$_GET['cid'] : 0;

if ($action === 'insert') {

    // anti-CSRF (Double Check)
    if (!XoopsSecurity::checkReferer()) {
        die('XOOPS_URL is not included in your REFERER');
    }

    // newly insert
    $sql  = 'INSERT INTO ' . $GLOBALS['xoopsDB']->prefix($table_cat) . ' SET ';
    $cols = array('pid' => 'I:N:0', 'title' => '50:E:1', 'imgurl' => '150:E:0');
    $sql .= mysqli_get_sql_set($cols);
    $GLOBALS['xoopsDB']->query($sql) || die('DB Error: insert category');

    // Check if cid == pid
    $cid = $GLOBALS['xoopsDB']->getInsertId();
    if ($cid == (int)$_POST['pid']) {
        $GLOBALS['xoopsDB']->query('UPDATE ' . $GLOBALS['xoopsDB']->prefix($table_cat) . " SET pid='0' WHERE cid='$cid'");
    }

    redirect_header('main.php', 1, _AM_CAT_INSERTED);
} elseif ($action === 'update' && !empty($_POST['cid'])) {

    // anti-CSRF (Double Check)
    if (!XoopsSecurity::checkReferer()) {
        die('XOOPS_URL is not included in your REFERER');
    }

    $cid = (int)$_POST['cid'];
    $pid = (int)$_POST['pid'];

    // Check if new pid was a child of cid
    if ($pid != 0) {
        foreach ($cattree->getAllChild($cid) as $child) {
            $children[$child->getVar('cid')] = $child->getVar('cid');
        }
        foreach ($children as $child) {
            if ($child == $pid) {
                die('category looping has occurred');
            }
        }
    }

    // update
    $sql  = 'UPDATE ' . $GLOBALS['xoopsDB']->prefix($table_cat) . ' SET ';
    $cols = array('pid' => 'I:N:0', 'title' => '50:E:1', 'imgurl' => '150:E:0');
    $sql .= mysqli_get_sql_set($cols) . " WHERE cid='$cid'";
    $GLOBALS['xoopsDB']->query($sql) || die('DB Error: update category');
    redirect_header('main.php', 1, _AM_CAT_UPDATED);
} elseif (!empty($_POST['delcat'])) {

    // anti-CSRF (Double Check)
    if (!XoopsSecurity::checkReferer()) {
        die('XOOPS_URL is not included in your REFERER');
    }

    // Delete
    $cid = (int)$_POST['delcat'];

    $children[0] = 0;
    //get all categories under the specified category
    foreach ($GLOBALS['cattree']->getAllChild($cid) as $child) {
        $children[$child->getVar('cid')] = $child->getVar('cid');
    }
    $whr = 'cid IN (';
    foreach ($children as $child) {
        $whr .= "$child,";
        xoops_notification_deletebyitem($myalbum_mid, 'category', $child);
    }
    $whr .= "$cid)";
    xoops_notification_deletebyitem($myalbum_mid, 'category', $cid);
    $criteria = new Criteria('`cid`', '(' . implode(',', $children) . ')', 'IN');
    myalbum_delete_photos($criteria);
    $GLOBALS['xoopsDB']->query('DELETE FROM ' . $GLOBALS['xoopsDB']->prefix($table_cat) . " WHERE $whr")
    || die('DB error: DELETE cat table');
    redirect_header('main.php', 2, _ALBM_CATDELETED);
} elseif (!empty($_POST['batch_update'])) {
}

//
// Form Part
//
xoops_cp_header();
// myalbum_adminMenu(basename(__FILE__), 1);

// check $xoopsModule
if (!is_object($xoopsModule)) {
    redirect_header("$mod_url/", 1, _NOPERM);
}
//echo "<h3 style='text-align:left;'>".sprintf( _AM_H3_FMT_CATEGORIES , $xoopsModule->name() )."</h3>\n" ;
$indexAdmin = new ModuleAdmin();
echo $indexAdmin->addNavigation(basename(__FILE__));

if ($disp === 'edit' && $cid > 0) {

    // Editing
    $sql       = 'SELECT cid,pid,weight,title,imgurl FROM ' . $GLOBALS['xoopsDB']->prefix($table_cat) . " WHERE cid='$cid'";
    $crs       = $GLOBALS['xoopsDB']->query($sql);
    $cat_array = $GLOBALS['xoopsDB']->fetchArray($crs);
    echo myalbum_admin_form_display_edit($cat_array, _AM_CAT_MENU_EDIT, 'update');
} elseif ($disp === 'new') {

    // New
    $cat_array = array('cid' => 0, 'pid' => $cid, 'weight' => 0, 'title' => '', 'imgurl' => 'http://');
    echo myalbum_admin_form_display_edit($cat_array, _AM_CAT_MENU_NEW, 'insert');
} else {

    // Listing
    $live_cids = array(0 => '0');
    foreach ($cattree->getAllChild($cid, array()) as $child) {
        $cat_tree_array[$child->getVar('cid')] = $child->toArray();
        $live_cids[$child->getVar('cid')]      = $child->getVar('cid');
    }
    $criteria = new CriteriaCompo(new Criteria('`pid`', '(' . implode(',', $live_cids) . ')', 'NOT IN'));
    if ($catHandler->getCount($criteria) !== false) {
        $GLOBALS['xoopsDB']->queryF('UPDATE ' . $GLOBALS['xoopsDB']->prefix($table_cat) . " SET pid='0' " . $criteria->renderWhere());
        redirect_header('index.php', 0, 'A Ghost Category found.');
    }

    // Waiting Admission
    $criteria       = new Criteria('`status`', '0');
    $waiting        = $photosHandler->getCount($criteria);
    $link_admission = $waiting > 0 ? sprintf(_AM_CAT_FMT_NEEDADMISSION, $waiting) : '';

    // Top links
    //  echo "<p><a href='?disp=new&cid=0'>"._AM_CAT_LINK_MAKETOPCAT."<img src='../assets/images/cat_add.gif' width='18' height='15' alt='"._AM_CAT_LINK_MAKETOPCAT."' title='"._AM_CAT_LINK_MAKETOPCAT."' /></a> &nbsp;  &nbsp; <a href='admission.php' style='color:red;'>$link_admission</a></p>\n" ;
    $indexAdmin->addItemButton(_AM_CAT_LINK_MAKETOPCAT, '?disp=new&cid=0', 'add', '');
    echo $indexAdmin->renderButton('left', '');

    // TH
    echo "
    <form name='MainForm' action='' method='post' style='margin:10px;'>
    <input type='hidden' name='delcat' value='' />
    <table width='75%' class='outer' cellpadding='4' cellspacing='1'>
      <tr valign='middle'>
        <th>" . _AM_CAT_TH_TITLE . '</th>
        <th>' . _AM_CAT_TH_WEIGHT . '</th>
        <th>' . _AM_CAT_TH_PHOTOS . '</th>
        <th>' . _AM_CAT_TH_OPERATION . "</th>
        <th nowrap='nowrap'>" . _AM_CAT_TH_IMAGE . '</th>
      </tr>
    ';

    // TD
    $oddeven = 'odd';
    if (isset($cat_tree_array)) {
        foreach ($cat_tree_array as $cid => $cat_node) {
            $oddeven = $oddeven === 'odd' ? 'even' : 'odd';
            extract($cat_node);
            $prefix      = '';
            $prefix      = str_repeat('&nbsp;--', $catHandler->prefixDepth($cid, 0));
            $cid         = (int)$cid;
            $del_confirm = 'confirm("' . sprintf(_AM_CAT_FMT_CATDELCONFIRM, $title) . '")';
            $criteria    = new Criteria('`cid`', $cid);
            $photos_num  = $photosHandler->getCount($criteria);
            if ($imgurl && $imgurl !== 'http://') {
                $imgsrc4show = $GLOBALS['myts']->htmlSpecialChars($imgurl);
            } else {
                $imgsrc4show = '../assets/images/pixel_trans.gif';
            }

            echo "<tr>
            <td class='$oddeven' width='100%'><a href='photomanager.php?cid=$cid'>$prefix&nbsp;" . $GLOBALS['myts']->htmlSpecialChars($title) . "</a></td>
            <td class='$oddeven' nowrap='nowrap' align='right'>
              <a href='photomanager.php?cid=$cid'>$photos_num</a>
              <a href='../submit.php?cid=$cid'><img src='" . $pathIcon16 . "/add.png' width='16' height='16' alt='" . _AM_CAT_LINK_ADDPHOTOS . "' title='" . _AM_CAT_LINK_ADDPHOTOS . "' /></a></td>
            <td class='$oddeven' align='center' nowrap='nowrap'>
              &nbsp;
              <a href='?disp=edit&amp;cid=$cid'><img src='" . $pathIcon16 . "/edit.png' width='16' height='16' alt='" . _AM_CAT_LINK_EDIT . "' title='" . _AM_CAT_LINK_EDIT . "' /></a>
              &nbsp;
              <a href='?disp=new&amp;cid=$cid'><img src='" . $pathIcon16 . "/folder_add.png' width='16' height='16' alt='" . _AM_CAT_LINK_MAKESUBCAT . "' title='" . _AM_CAT_LINK_MAKESUBCAT . "' /></a>
              &nbsp;
              <input type='button' value='" . _DELETE . "' onclick='if ($del_confirm) {document.MainForm.delcat.value=\"$cid\"; submit();}' />
            </td>
            <td class='$oddeven' align='center'><img src='$imgsrc4show' height='16' /></td>
          </tr>\n";
        }
    }

    // Table footer
    echo "
      <!-- <tr>
        <td colspan='4' align='right' class='foot'><input type='submit' name='batch_update' value='" . _AM_CAT_BTN_BATCH . "' /></td>
      </tr> -->
    </table>
    </form>
    ";
}

//myalbum_footer_adminMenu();
//xoops_cp_footer();
include_once __DIR__ . '/admin_footer.php';
