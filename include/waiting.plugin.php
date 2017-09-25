<?php

// defined('XOOPS_ROOT_PATH') || exit('Restricted access.');

$moduleDirName = basename(dirname(__DIR__));

eval('

function b_waiting_' . $moduleDirName . '(){
    return b_waiting_myalbum_base( "' . $moduleDirName . '" ) ;
}

');

if (!function_exists('b_waiting_myalbum_base')) {

    /**
     * @param $moduleDirName
     *
     * @return array
     */
    function b_waiting_myalbum_base($moduleDirName)
    {
        $xoopsDB = XoopsDatabaseFactory::getDatabaseConnection();
        $block   = [];

        // get $mydirnumber
        if (!preg_match('/^(\D+)(\d*)$/', $moduleDirName, $regs)) {
            echo('invalid dirname: ' . htmlspecialchars($moduleDirName));
        }
        $mydirnumber = '' === $regs[2] ? '' : (int)$regs[2];

        $result = $xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix("myalbum{$mydirnumber}_photos") . ' WHERE status=0');
        if ($result) {
            $block['adminlink'] = XOOPS_URL . "/modules/myalbum{$mydirnumber}/admin/admission.php";
            list($block['pendingnum']) = $xoopsDB->fetchRow($result);
            $block['lang_linkname'] = _PI_WAITING_WAITINGS;
        }

        return $block;
    }
}
