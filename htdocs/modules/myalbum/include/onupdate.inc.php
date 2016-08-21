<?php

if (!defined('XOOPS_ROOT_PATH')) {
    exit;
}

$mydirname = basename(dirname(__DIR__));
if (!preg_match('/^(\D+)(\d*)$/', $mydirname, $regs)) {
    echo('invalid dirname: ' . htmlspecialchars($mydirname));
}
$mydirnumber = $regs[2] === '' ? '' : (int)$regs[2];

// referer check
$ref = xoops_getenv('HTTP_REFERER');
if ($ref === '' || strpos($ref, XOOPS_URL . '/modules/system/admin.php') === 0) {
    /* module specific part */
    $db =& XoopsDatabaseFactory::getDatabaseConnection();

    // 2.8 -> 2.9
    $check_result = $db->query('SELECT weight FROM ' . $db->prefix("myalbum{$mydirnumber}_cat"));
    if (!$check_result) {
        $db->query('ALTER TABLE ' . $db->prefix("myalbum{$mydirnumber}_cat")
                   . " ADD weight int(5) unsigned NOT NULL default 0, ADD depth int(5) unsigned NOT NULL default 0, ADD description text, ADD allowed_ext varchar(255) NOT NULL default 'jpg|jpeg|gif|png', ADD KEY (weight), ADD KEY (depth)");
        $db->query('ALTER TABLE ' . $db->prefix("myalbum{$mydirnumber}_photos") . ' ADD KEY (`date`)');
        $db->query('ALTER TABLE ' . $db->prefix("myalbum{$mydirnumber}_text") . ' DROP KEY lid');
        $db->query('ALTER TABLE ' . $db->prefix("myalbum{$mydirnumber}_text") . ' ADD PRIMARY KEY (lid)');
        $db->query('ALTER TABLE ' . $db->prefix("myalbum{$mydirnumber}_votedata") . ' ADD KEY (lid)');
    }

    /* General part */
    // Version 3.01
    $db->query('ALTER TABLE ' . $db->prefix("myalbum{$mydirnumber}_photos")
               . " ADD COLUMN tags varchar(255) NOT NULL default ''");
    // Keep the values of block's options when module is updated (by nobunobu)
    include __DIR__ . '/updateblock.inc.php';
}
