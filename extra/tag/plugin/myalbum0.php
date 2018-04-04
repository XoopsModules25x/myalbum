<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * XOOPS tag management module
 *
 * @copyright       The XOOPS project http://sourceforge.net/projects/xoops/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @since           1.0.0
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id: myalbum0.php 11905 2013-08-14 05:25:33Z beckmi $
 * @package         tag
 */

// defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Get item fields:
 * title
 * content
 * time
 * link
 * uid
 * uname
 * tags
 *
 * @var        array    $items    associative array of items: [modid][catid][itemid]
 *
 * @return    boolean
 *
 */
function myalbum0_tag_iteminfo(&$items)
{
    if (empty($items) || !is_array($items)) {
        return false;
    }
    
    $items_id = [];
    foreach (array_keys($items) as $cat_id) {
        // Some handling here to build the link upon catid
        // catid is not used in myalbum0, so just skip it
        foreach (array_keys($items[$cat_id]) as $item_id) {
            // In myalbum0, the item_id is "topic_id"
            $items_id[] = (int)$item_id;
        }
    }
    $item_handler = xoops_getModuleHandler('photos', 'myalbum0');
    $text_handler = xoops_getModuleHandler('text', 'myalbum0');
    $items_obj = $item_handler->getObjects(new \Criteria('lid', '(' . implode(', ', $items_id) . ')', 'IN'), true);
    
    foreach (array_keys($items) as $cat_id) {
        foreach (array_keys($items[$cat_id]) as $item_id) {
            $item_obj =& $items_obj[$item_id];
            $text = $text_handler->get($item_id);
            $items[$cat_id][$item_id] = [
                'title'   => $item_obj->getVar('title'),
                'uid'     => $item_obj->getVar('submitter'),
                'link'    => "photo.php?lid={$item_id}&cid=" . $item_obj->getVar('cid'),
                'time'    => $item_obj->getVar('date'),
                'tags'    => tag_parse_tag($item_obj->getVar('tags', 'n')),
                'content' => $GLOBALS['myts']->displayTarea($text->getVar('description'), 1, 1, 1, 1, 1, 1),
            ];
        }
    }
    unset($items_obj);
}

/**
 * Remove orphan tag-item links
 *
 * @param $mid
 * @return void
 */
function myalbum0_tag_synchronization($mid)
{
    $item_handler = xoops_getModuleHandler('photos', 'myalbum0');
    $link_handler = \XoopsModules\Tag\Helper::getInstance()->getHandler('Link'); //@var \XoopsModules\Tag\Handler $tagHandler
        
    /* clear tag-item links */
    if (version_compare(mysql_get_server_info(), '4.1.0', 'ge')):
    $sql =  "    DELETE FROM {$link_handler->table}" . '    WHERE ' .
            "        tag_modid = {$mid}" . '        AND ' . '        ( tag_itemid NOT IN ' .
            "            ( SELECT DISTINCT {$item_handler->keyName} " .
            "                FROM {$item_handler->table} " .
            "                WHERE {$item_handler->table}.approved > 0" . '            ) ' . '        )'; else:
    $sql =  "    DELETE {$link_handler->table} FROM {$link_handler->table}" .
            "    LEFT JOIN {$item_handler->table} AS aa ON {$link_handler->table}.tag_itemid = aa.{$item_handler->keyName} " . '    WHERE ' .
            "        tag_modid = {$mid}" . '        AND ' .
            "        ( aa.{$item_handler->keyName} IS NULL" . '            OR aa.approved < 1' . '        )';
    endif;
    if (!$result = $link_handler->db->queryF($sql)) {
        //xoops_error($link_handler->db->error());
    }
}
