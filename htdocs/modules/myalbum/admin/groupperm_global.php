<?php
include_once __DIR__ . '/admin_header.php';
include_once __DIR__ . '/mygrouppermform.php';

xoops_loadLanguage('admin', 'system');

if (!empty($_POST['submit'])) {
    include __DIR__ . '/mygroupperm.php';
    redirect_header(XOOPS_URL . '/modules/' . $xoopsModule->dirname() . '/admin/groupperm_global.php', 1, _AM_ALBM_GPERMUPDATED);
}

xoops_cp_header();
$indexAdmin = new ModuleAdmin();
echo $indexAdmin->addNavigation('groupperm_global.php');
//myalbum_adminMenu(basename(__FILE__), 8);
$GLOBALS['xoopsTpl']->assign('admin_title', $GLOBALS['myalbumModule']->name());
$GLOBALS['xoopsTpl']->assign('mydirname', $GLOBALS['mydirname']);
$GLOBALS['xoopsTpl']->assign('photos_url', $GLOBALS['photos_url']);
$GLOBALS['xoopsTpl']->assign('thumbs_url', $GLOBALS['thumbs_url']);
$GLOBALS['xoopsTpl']->assign('form', myalbum_admin_form_groups());
if (isset($result_str)) {
    $GLOBALS['xoopsTpl']->assign('result_str', $result_str);
}

$GLOBALS['xoopsTpl']->display('db:' . $GLOBALS['mydirname'] . '_cpanel_permissions.tpl');

//  myalbum_footer_adminMenu();
include_once __DIR__ . '/admin_footer.php';
