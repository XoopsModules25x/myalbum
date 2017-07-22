<?php
/**
 * @param $options
 *
 * @return array
 */
function myalbum0_tag_block_cloud_show($options)
{
    require_once XOOPS_ROOT_PATH . '/modules/tag/blocks/block.php';

    return tag_block_cloud_show($options, $module_dirname);
}

/**
 * @param $options
 *
 * @return string
 */
function myalbum0_tag_block_cloud_edit($options)
{
    require_once XOOPS_ROOT_PATH . '/modules/tag/blocks/block.php';

    return tag_block_cloud_edit($options);
}

/**
 * @param $options
 *
 * @return array
 */
function myalbum0_tag_block_top_show($options)
{
    require_once XOOPS_ROOT_PATH . '/modules/tag/blocks/block.php';

    return tag_block_top_show($options, $module_dirname);
}

/**
 * @param $options
 *
 * @return string
 */
function myalbum0_tag_block_top_edit($options)
{
    require_once XOOPS_ROOT_PATH . '/modules/tag/blocks/block.php';

    return tag_block_top_edit($options);
}
