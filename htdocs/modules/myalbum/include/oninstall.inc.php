<?php

if (!defined('XOOPS_ROOT_PATH')) {
    exit;
}

$mydirname = $_SESSION['myalbum_mydirname'];
include_once XOOPS_ROOT_PATH . "/modules/$mydirname/language/english/myalbum_constants.php";

eval('
function xoops_module_install_' . $mydirname . '( $module )
{
    $modid = $module->getVar("mid") ;
    $gperm_handler = xoops_gethandler("groupperm");

    $global_perms_array = array(
        GPERM_INSERTABLE => _ALBM_GPERM_G_INSERTABLE ,
        GPERM_SUPERINSERT | GPERM_INSERTABLE => _ALBM_GPERM_G_SUPERINSERT ,
//		GPERM_EDITABLE => _ALBM_GPERM_G_EDITABLE ,
        GPERM_SUPEREDIT | GPERM_EDITABLE => _ALBM_GPERM_G_SUPEREDIT ,
//		GPERM_DELETABLE => _ALBM_GPERM_G_DELETABLE ,
        GPERM_SUPERDELETE | GPERM_DELETABLE => _ALBM_GPERM_G_SUPERDELETE ,
        GPERM_RATEVIEW => _ALBM_GPERM_G_RATEVIEW ,
        GPERM_RATEVOTE | GPERM_RATEVIEW => _ALBM_GPERM_G_RATEVOTE
    ) ;

    foreach ($global_perms_array as $perms_id => $perms_name) {
        $gperm =& $gperm_handler->create();
        $gperm->setVar("gperm_groupid", XOOPS_GROUP_ADMIN);
        $gperm->setVar("gperm_name", "myalbum_global");
        $gperm->setVar("gperm_modid", $modid);
        $gperm->setVar("gperm_itemid", $perms_id );
        $gperm_handler->insert($gperm) ;
        unset($gperm);
    }
}

');
