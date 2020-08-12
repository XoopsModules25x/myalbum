<?php

use XoopsModules\Myalbum;



$moduleDirName = $_SESSION['myalbum_mydirname'];
require_once XOOPS_ROOT_PATH . "/modules/$moduleDirName/language/english/myalbum_constants.php";

eval(
    '
function xoops_module_install_' . $moduleDirName . '( $module )
{
    $modid = $module->getVar("mid") ;
    $grouppermHandler = xoops_getHandler("groupperm");

    $global_perms_array = [
        GPERM_INSERTABLE => _ALBM_GPERM_G_INSERTABLE ,
        GPERM_SUPERINSERT | GPERM_INSERTABLE => _ALBM_GPERM_G_SUPERINSERT ,
//      GPERM_EDITABLE => _ALBM_GPERM_G_EDITABLE ,
        GPERM_SUPEREDIT | GPERM_EDITABLE => _ALBM_GPERM_G_SUPEREDIT ,
//      GPERM_DELETABLE => _ALBM_GPERM_G_DELETABLE ,
        GPERM_SUPERDELETE | GPERM_DELETABLE => _ALBM_GPERM_G_SUPERDELETE ,
        GPERM_RATEVIEW => _ALBM_GPERM_G_RATEVIEW ,
        GPERM_RATEVOTE | GPERM_RATEVIEW => _ALBM_GPERM_G_RATEVOTE
    ] ;

    foreach ($global_perms_array as $perms_id => $perms_name) {
        $gperm = $grouppermHandler->create();
        $gperm->setVar("gperm_groupid", XOOPS_GROUP_ADMIN);
        $gperm->setVar("gperm_name", "myalbum_global");
        $gperm->setVar("gperm_modid", $modid);
        $gperm->setVar("gperm_itemid", $perms_id );
        $grouppermHandler->insert($gperm) ;
        unset($gperm);
    }

//    require_once $GLOBALS["xoops"]->path("modules/' . $moduleDirName . '/config/config.php");
//    require_once $GLOBALS[\'xoops\']->path(\'modules/' . $moduleDirName . '/class/Utility.php\');
    foreach (array_keys($uploadFolders) as $i) {
        Myalbum\Utility::createFolder($uploadFolders[$i]);
    }


}

'
);

/*
function xoops_module_install_myalbum(\XoopsModule $xoopsModule)
{
    require_once dirname(dirname(dirname(__DIR__))) . '/mainfile.php';

    xoops_loadLanguage('admin', $xoopsModule->getVar('dirname'));
    xoops_loadLanguage('modinfo', $xoopsModule->getVar('dirname'));

    $moduleDirName = $xoopsModule->getVar('dirname');
    require_once $GLOBALS['xoops']->path('modules/' . $moduleDirName . '/config/config.php');

    foreach (array_keys($uploadFolders) as $i) {
        Myalbum\Utility::createFolder($uploadFolders[$i]);
    }

    $file = _ALMB_ROOT_PATH . '/assets/images/blank.png';
    foreach (array_keys($copyFiles) as $i) {
        $dest = $copyFiles[$i] . '/blank.png';
        Myalbum\Utility::copyFile($file, $dest);
    }

    return true;

}
*/
