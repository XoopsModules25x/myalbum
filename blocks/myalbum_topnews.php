<?php

if (!defined('MYALBUM_BLOCK_TOPNEWS_INCLUDED')) {
    define('MYALBUM_BLOCK_TOPNEWS_INCLUDED', 1);

    /**
     * @param $options
     *
     * @return array
     */
    function b_myalbum_topnews_show($options)
    {
        global $xoopsDB, $mod_url, $table_photos, $myalbum_normal_exts;

        // For myAlbum-P < 2.70
        if (0 != strncmp($options[0], 'myalbum', 7)) {
            $title_max_length = (int)$options[1];
            $photos_num       = (int)$options[0];
            $moduleDirName    = 'myalbum';
        } else {
            $title_max_length = (int)$options[2];
            $photos_num       = (int)$options[1];
            $moduleDirName    = $options[0];
        }
        $cat_limitation      = empty($options[3]) ? 0 : (int)$options[3];
        $cat_limit_recursive = empty($options[4]) ? 0 : 1;
        $cols                = empty($options[6]) ? 1 : (int)$options[6];

        require_once XOOPS_ROOT_PATH . "/modules/$moduleDirName/include/read_configs.php";

        // Category limitation
        if ($cat_limitation) {
            if ($cat_limit_recursive) {
                require_once XOOPS_ROOT_PATH . '/class/xoopstree.php';
                $cattree  = new \XoopsTree($GLOBALS['xoopsDB']->prefix($table_cat), 'cid', 'pid');
                $children = $cattree->getAllChildId($cat_limitation);
                $whr_cat  = 'cid IN (';
                foreach ($children as $child) {
                    $whr_cat .= "$child,";
                }
                $whr_cat .= "$cat_limitation)";
            } else {
                $whr_cat = "cid='$cat_limitation'";
            }
        } else {
            $whr_cat = '1';
        }

        $block           = [];
        $GLOBALS['myts'] = \MyTextSanitizer::getInstance();
        $result          = $xoopsDB->query('SELECT lid , cid , title , ext , res_x , res_y , submitter , `status` , date AS unixtime , hits , rating , votes , comments FROM ' . $xoopsDB->prefix($table_photos) . " WHERE status>0 AND $whr_cat ORDER BY unixtime DESC", $photos_num, 0);
        $count           = 1;
        while (false !== ($photo = $xoopsDB->fetchArray($result))) {
            $photo['title'] = $GLOBALS['myts']->displayTarea($photo['title']);
            if (mb_strlen($photo['title']) >= $title_max_length) {
                if (!XOOPS_USE_MULTIBYTES) {
                    $photo['title'] = mb_substr($photo['title'], 0, $title_max_length - 1) . '...';
                } elseif (function_exists('mb_strcut')) {
                    $photo['title'] = mb_strcut($photo['title'], 0, $title_max_length - 1) . '...';
                }
            }
            $photo['suffix']     = $photo['hits'] > 1 ? 'hits' : 'hit';
            $photo['date']       = formatTimestamp($photo['unixtime'], 's');
            $photo['thumbs_url'] = $thumbs_url;

            if (in_array(mb_strtolower($photo['ext']), $myalbum_normal_exts)) {
                $width_spec = "width='$myalbum_thumbsize'";
                if ($myalbum_makethumb) {
                    [$width, $height, $type] = getimagesize("$thumbs_dir/{$photo['lid']}.{$photo['ext']}");
                    if ($width <= $myalbum_thumbsize) { // if thumb images was made, 'width' and 'height' will not set.
                        $width_spec = '';
                    }
                }
                $photo['width_spec'] = $width_spec;
            } else {
                $photo['ext']        = 'gif';
                $photo['width_spec'] = '';
            }

            $block['photo'][$count++] = $photo;
        }
        $block['mod_url'] = $mod_url;
        $block['cols']    = $cols;

        return $block;
    }

    /**
     * @param $options
     *
     * @return string
     */
    function b_myalbum_topnews_edit($options)
    {
        global $xoopsDB;

        // For myAlbum-P < 2.70
        if (0 != strncmp($options[0], 'myalbum', 7)) {
            $title_max_length = (int)$options[1];
            $photos_num       = (int)$options[0];
            $moduleDirName    = 'myalbum';
        } else {
            $title_max_length = (int)$options[2];
            $photos_num       = (int)$options[1];
            $moduleDirName    = $options[0];
        }
        $cat_limitation      = empty($options[3]) ? 0 : (int)$options[3];
        $cat_limit_recursive = empty($options[4]) ? 0 : 1;
        $cols                = empty($options[6]) ? 1 : (int)$options[6];

        require_once XOOPS_ROOT_PATH . '/class/xoopstree.php';
        $cattree = new \XoopsTree($xoopsDB->prefix("{$moduleDirName }_cat"), 'cid', 'pid');

        ob_start();
        $cattree->makeMySelBox('title', 'title', $cat_limitation, 1, 'options[3]');
        $catselbox = ob_get_clean();

        return '
        ' . _ALBM_TEXT_DISP . " &nbsp;
        <input type='hidden' name='options[0]' value='{$moduleDirName }'>
        <input type='text' size='4' name='options[1]' value='$photos_num' style='text-align:right;'>
        <br>
        " . _ALBM_TEXT_STRLENGTH . " &nbsp;
        <input type='text' size='6' name='options[2]' value='$title_max_length' style='text-align:right;'>
        <br>
        " . _ALBM_TEXT_CATLIMITATION . " &nbsp; $catselbox
        " . _ALBM_TEXT_CATLIMITRECURSIVE . "
        <input type='radio' name='options[4]' value='1' " . ($cat_limit_recursive ? 'checked' : '') . '>' . _YES . "
        <input type='radio' name='options[4]' value='0' " . ($cat_limit_recursive ? '' : 'checked') . '>' . _NO . "
        <br>
        <input type='hidden' name='options[5]' value=''>
        " . _ALBM_TEXT_COLS . "&nbsp;
        <input type='text' size='2' name='options[6]' value='$cols' style='text-align:right;'>
        <br>
        \n";
    }
}
