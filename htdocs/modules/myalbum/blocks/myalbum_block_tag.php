<?php


function myalbum_tag_block_cloud_show($options) 
{
    if (file_exists($fileinc = XOOPS_ROOT_PATH . "/modules/tag/blocks/block.php")) {
    global $module_dirname;
    include_once XOOPS_ROOT_PATH . "/modules/tag/blocks/block.php";
    return tag_block_cloud_show($options, $module_dirname);
    }
}
function myalbum_tag_block_cloud_edit($options) 
{
    if (file_exists($fileinc = XOOPS_ROOT_PATH . "/modules/tag/blocks/block.php")) {
    include_once XOOPS_ROOT_PATH . "/modules/tag/blocks/block.php";
    return tag_block_cloud_edit($options);
    }
}
function myalbum_tag_block_top_show($options) 
{
    if (file_exists($fileinc = XOOPS_ROOT_PATH . "/modules/tag/blocks/block.php")) {
    global $module_dirname;
    include_once XOOPS_ROOT_PATH . "/modules/tag/blocks/block.php";
    return tag_block_top_show($options, $module_dirname);
    }
}
function myalbum_tag_block_top_edit($options) 
{
    if (file_exists($fileinc = XOOPS_ROOT_PATH . "/modules/tag/blocks/block.php")) {
    include_once XOOPS_ROOT_PATH . "/modules/tag/blocks/block.php";
    return tag_block_top_edit($options);
    }
}

