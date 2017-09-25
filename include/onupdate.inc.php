<?php

// defined('XOOPS_ROOT_PATH') || exit('Restricted access.');

$moduleDirName = basename(dirname(__DIR__));
if (!preg_match('/^(\D+)(\d*)$/', $moduleDirName, $regs)) {
    echo('invalid dirname: ' . htmlspecialchars($moduleDirName));
}
$mydirnumber = '' === $regs[2] ? '' : (int)$regs[2];

// referer check
$ref = xoops_getenv('HTTP_REFERER');
if ('' == $ref || 0 == strpos($ref, XOOPS_URL . '/modules/system/admin.php')) {
    /* module specific part */
    $db = XoopsDatabaseFactory::getDatabaseConnection();

    // 2.8 -> 2.9
    $check_result = $db->query('SELECT weight FROM ' . $db->prefix("myalbum{$mydirnumber}_cat"));
    if (!$check_result) {
        $db->query('ALTER TABLE ' . $db->prefix("myalbum{$mydirnumber}_cat") . " ADD weight INT(5) UNSIGNED NOT NULL DEFAULT 0, ADD depth INT(5) UNSIGNED NOT NULL DEFAULT 0, ADD description TEXT, ADD allowed_ext VARCHAR(255) NOT NULL DEFAULT 'jpg|jpeg|gif|png', ADD KEY (weight), ADD KEY (depth)");
        $db->query('ALTER TABLE ' . $db->prefix("myalbum{$mydirnumber}_photos") . ' ADD KEY (`date`)');
        $db->query('ALTER TABLE ' . $db->prefix("myalbum{$mydirnumber}_text") . ' DROP KEY lid');
        $db->query('ALTER TABLE ' . $db->prefix("myalbum{$mydirnumber}_text") . ' ADD PRIMARY KEY (lid)');
        $db->query('ALTER TABLE ' . $db->prefix("myalbum{$mydirnumber}_votedata") . ' ADD KEY (lid)');
    }

    /* General part */
    // Version 3.01
    $db->query('ALTER TABLE ' . $db->prefix("myalbum{$mydirnumber}_photos") . " ADD COLUMN tags VARCHAR(255) NOT NULL DEFAULT ''");
    // Keep the values of block's options when module is updated (by nobunobu)
    include __DIR__ . '/updateblock.inc.php';
}

function xoops_module_update_myalbum(XoopsModule $module, $oldversion = null)
{
    //create upload directories, if needed
    $moduleDirName = $module->getVar('dirname');
    include $GLOBALS['xoops']->path('modules/' . $moduleDirName . '/include/config.php');
    include $GLOBALS['xoops']->path('modules/' . $moduleDirName . '/class/utility.php');

    foreach (array_keys($uploadFolders) as $i) {
        MyalbumUtility::createFolder($uploadFolders[$i]);
    }
    //copy blank.png files, if needed
    $file = _ALBM_ROOT_PATH . '/assets/images/blank.png';
    foreach (array_keys($copyFiles) as $i) {
        $dest = $copyFiles[$i] . '/blank.png';
        MyalbumUtility::copyFile($file, $dest);
    }

    $gpermHandler = xoops_getHandler('groupperm');

    return $gpermHandler->deleteByModule($module->getVar('mid'), 'item_read');
}
