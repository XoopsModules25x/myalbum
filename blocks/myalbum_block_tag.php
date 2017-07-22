<?php

/**
 * @param $options
 *
 * @return array|null
 */
function myalbum_tag_block_cloud_show($options)
{
    if (file_exists($fileinc = XOOPS_ROOT_PATH . '/modules/tag/blocks/block.php')) {
        global $module_dirname;
        include_once XOOPS_ROOT_PATH . '/modules/tag/blocks/block.php';

        return tag_block_cloud_show($options, $module_dirname);
    }

    return null;
}

/**
 * @param $options
 *
 * @return null|string
 */
function myalbum_tag_block_cloud_edit($options)
{
    if (file_exists($fileinc = XOOPS_ROOT_PATH . '/modules/tag/blocks/block.php')) {
        include_once XOOPS_ROOT_PATH . '/modules/tag/blocks/block.php';

        return tag_block_cloud_edit($options);
    }

    return null;
}

/**
 * @param $options
 *
 * @return array|null
 */
function myalbum_tag_block_top_show($options)
{
    if (file_exists($fileinc = XOOPS_ROOT_PATH . '/modules/tag/blocks/block.php')) {
        global $module_dirname;
        include_once XOOPS_ROOT_PATH . '/modules/tag/blocks/block.php';

        return tag_block_top_show($options, $module_dirname);
    }

    return null;
}

/**
 * @param $options
 *
 * @return null|string
 */
function myalbum_tag_block_top_edit($options)
{
    if (file_exists($fileinc = XOOPS_ROOT_PATH . '/modules/tag/blocks/block.php')) {
        include_once XOOPS_ROOT_PATH . '/modules/tag/blocks/block.php';

        return tag_block_top_edit($options);
    }

    return null;
}
