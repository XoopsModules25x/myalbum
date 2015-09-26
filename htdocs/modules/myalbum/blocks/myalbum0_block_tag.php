<?php
/**
 * @param $options
 * @return array
 */
function myalbum0_tag_block_cloud_show($options)
{
    include_once XOOPS_ROOT_PATH . "/modules/tag/blocks/block.php";

    return tag_block_cloud_show($options, $module_dirname);
}

/**
 * @param $options
 * @return string
 */
function myalbum0_tag_block_cloud_edit($options)
{
    include_once XOOPS_ROOT_PATH . "/modules/tag/blocks/block.php";

    return tag_block_cloud_edit($options);
}

/**
 * @param $options
 * @return array
 */
function myalbum0_tag_block_top_show($options)
{
    include_once XOOPS_ROOT_PATH . "/modules/tag/blocks/block.php";

    return tag_block_top_show($options, $module_dirname);
}

/**
 * @param $options
 * @return string
 */
function myalbum0_tag_block_top_edit($options)
{
    include_once XOOPS_ROOT_PATH . "/modules/tag/blocks/block.php";

    return tag_block_top_edit($options);
}
