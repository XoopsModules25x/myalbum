<?php

if (!defined('MYALBUM_BLOCK_RPHOTO_INCLUDED')) {
    define('MYALBUM_BLOCK_RPHOTO_INCLUDED', 1);

    /**
     * @param $options
     *
     * @return array
     */
    function b_myalbum_rphoto_show($options)
    {
        global $xoopsDB, $mod_url, $table_photos, $myalbum_normal_exts;

        // For myAlbum-P < 2.70
        if (0 != strncmp($options[0], 'myalbum', 7)) {
            $photos_num    = (int)$options[1];
            $box_size      = (int)$options[0];
            $moduleDirName = 'myalbum';
        } else {
            $photos_num    = (int)$options[2];
            $box_size      = (int)$options[1];
            $moduleDirName = $options[0];
        }
        $cat_limitation      = empty($options[3]) ? 0 : (int)$options[3];
        $cat_limit_recursive = empty($options[4]) ? 0 : 1;
        $cycle               = empty($options[5]) ? 60 : (int)$options[5];
        $cols                = empty($options[6]) ? 1 : (int)$options[6];

        include XOOPS_ROOT_PATH . "/modules/$moduleDirName/include/read_configs.php";

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

        // WHERE clause for ext
        // $whr_ext = "ext IN ('" . implode( "','" , $myalbum_normal_exts ) . "')" ;
        $whr_ext = '1';

        $block           = [];
        $GLOBALS['myts'] = \MyTextSanitizer::getInstance();
        // Get number of photo
        $result = $xoopsDB->query('SELECT count(lid) FROM ' . $xoopsDB->prefix($table_photos) . " WHERE status>0 AND $whr_cat AND $whr_ext");
        list($numrows) = $xoopsDB->fetchRow($result);
        if ($numrows < 1) {
            return $block;
        }

        if ($numrows <= $photos_num) {
            $result = $xoopsDB->query('SELECT lid , cid , title , ext , res_x , res_y , submitter , status , date AS unixtime , hits , rating , votes , comments FROM ' . $xoopsDB->prefix($table_photos) . " WHERE status>0 AND $whr_cat AND $whr_ext");
        } else {
            $result   = $xoopsDB->query('SELECT lid FROM ' . $xoopsDB->prefix($table_photos) . " WHERE status>0 AND $whr_cat AND $whr_ext");
            $lids     = [];
            $sel_lids = [];
            while (false !== (list($lid) = $xoopsDB->fetchRow($result))) {
                $lids[] = $lid;
            }
            mt_srand((int)(time() / $cycle) * $cycle);
            $sel_lids = array_rand($lids, $photos_num);
            if (is_array($sel_lids)) {
                $whr_lid = '';
                foreach ($sel_lids as $key) {
                    $whr_lid .= $lids[$key] . ',';
                }
                $whr_lid = substr($whr_lid, 0, -1);
            } else {
                $whr_lid = $lids[$sel_lids];
            }
            $result = $xoopsDB->query('SELECT lid , cid , title , ext , res_x , res_y , submitter , status , date AS unixtime , hits , rating , votes , comments FROM ' . $xoopsDB->prefix($table_photos) . " WHERE status>0 AND lid IN ($whr_lid)");
        }

        $count = 1;
        while (false !== ($photo = $xoopsDB->fetchArray($result))) {
            $photo['title']      = $GLOBALS['myts']->displayTarea($photo['title']);
            $photo['suffix']     = $photo['hits'] > 1 ? 'hits' : 'hit';
            $photo['date']       = formatTimestamp($photo['unixtime'], 's');
            $photo['thumbs_url'] = $thumbs_url;

            if (in_array(strtolower($photo['ext']), $myalbum_normal_exts)) {
                // width&height attirbs for <img>
                if ($box_size <= 0) {
                    $photo['img_attribs'] = '';
                } else {
                    list($width, $height, $type) = getimagesize("$thumbs_dir/{$photo['lid']}.{$photo['ext']}");
                    if ($width > $box_size || $height > $box_size) {
                        if ($width > $height) {
                            $photo['img_attribs'] = "width='$box_size'";
                        } else {
                            $photo['img_attribs'] = "height='$box_size'";
                        }
                    } else {
                        $photo['img_attribs'] = '';
                    }
                }
            } else {
                $photo['ext']         = 'gif';
                $photo['img_attribs'] = '';
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
    function b_myalbum_rphoto_edit($options)
    {
        global $xoopsDB;

        // For myAlbum-P < 2.70
        if (0 != strncmp($options[0], 'myalbum', 7)) {
            $photos_num    = (int)$options[1];
            $box_size      = (int)$options[0];
            $moduleDirName = 'myalbum';
        } else {
            $photos_num    = (int)$options[2];
            $box_size      = (int)$options[1];
            $moduleDirName = $options[0];
        }
        $cat_limitation      = empty($options[3]) ? 0 : (int)$options[3];
        $cat_limit_recursive = empty($options[4]) ? 0 : 1;
        $cycle               = empty($options[5]) ? 60 : (int)$options[5];
        $cols                = empty($options[6]) ? 1 : (int)$options[6];

        require_once XOOPS_ROOT_PATH . '/class/xoopstree.php';
        $cattree = new \XoopsTree($xoopsDB->prefix("{$moduleDirName }_cat"), 'cid', 'pid');

        ob_start();
        $cattree->makeMySelBox('title', 'title', $cat_limitation, 1, 'options[3]');
        $catselbox = ob_get_contents();
        ob_end_clean();

        $form = '
        ' . _ALBM_TEXT_BLOCK_WIDTH . "&nbsp;
        <input type='hidden' name='options[0]' value='{$moduleDirName }' >
        <input type='text' size='6' name='options[1]' value='$box_size' style='text-align:right;' >&nbsp;pixel " . _ALBM_TEXT_BLOCK_WIDTH_NOTES . '
        <br>
        ' . _ALBM_TEXT_DISP . "&nbsp;
        <input type='text' size='3' name='options[2]' value='$photos_num' style='text-align:right;' >
        <br>
        " . _ALBM_TEXT_CATLIMITATION . " &nbsp; $catselbox
        " . _ALBM_TEXT_CATLIMITRECURSIVE . "
        <input type='radio' name='options[4]' value='1' " . ($cat_limit_recursive ? 'checked' : '') . '>' . _YES . "
        <input type='radio' name='options[4]' value='0' " . ($cat_limit_recursive ? '' : 'checked') . '>' . _NO . '
        <br>
        ' . _ALBM_TEXT_RANDOMCYCLE . "&nbsp;
        <input type='text' size='6' name='options[5]' value='$cycle' style='text-align:right;' >
        <br>
        " . _ALBM_TEXT_COLS . "&nbsp;
        <input type='text' size='2' name='options[6]' value='$cols' style='text-align:right;' >
        <br>
        \n";

        return $form;
    }
}
