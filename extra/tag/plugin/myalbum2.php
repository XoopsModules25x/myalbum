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
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @since           1.0.0
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id: myalbum2.php 11905 2013-08-14 05:25:33Z beckmi $
 * @package         tag
 * @param mixed $items
 */



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
 * @return    bool
 * @var        array $items associative array of items: [modid][catid][itemid]
 *
 */
function myalbum2_tag_iteminfo(&$items)
{
    if (empty($items) || !is_array($items)) {
        return false;
    }

    $items_id = [];
    foreach (array_keys($items) as $cat_id) {
        // Some handling here to build the link upon catid
        // catid is not used in myalbum2, so just skip it
        foreach (array_keys($items[$cat_id]) as $item_id) {
            // In myalbum2, the item_id is "topic_id"
            $items_id[] = (int)$item_id;
        }
    }
    $itemHandler = $helper->getHandler('Photos', 'myalbum2');
    $textHandler = $helper->getHandler('Text', 'myalbum2');
    $items_obj   = $itemHandler->getObjects(new \Criteria('lid', '(' . implode(', ', $items_id) . ')', 'IN'), true);

    foreach (array_keys($items) as $cat_id) {
        foreach (array_keys($items[$cat_id]) as $item_id) {
            $item_obj                 = &$items_obj[$item_id];
            $text                     = $textHandler->get($item_id);
            $items[$cat_id][$item_id] = [
                'title'   => $item_obj->getVar('title'),
                'uid'     => $item_obj->getVar('submitter'),
                'link'    => "photo.php?lid={$item_id}&cid=" . $item_obj->getVar('cid'),
                'time'    => $item_obj->getVar('date'),
                'tags'    => \XoopsModules\Tag\Utility::tag_parse_tag($item_obj->getVar('tags', 'n')),
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
 */
function myalbum2_tag_synchronization($mid)
{
    $itemHandler = $helper->getHandler('Photos', 'myalbum2');
    $linkHandler = \XoopsModules\Tag\Helper::getInstance()->getHandler('Link'); //@var \XoopsModules\Tag\Handler $tagHandler

    /* clear tag-item links */
    if (version_compare($GLOBALS['xoopsDB']->getServerVersion(), '4.1.0', 'ge')):
        $sql = "    DELETE FROM {$linkHandler->table}"
               . '    WHERE '
               . "        tag_modid = {$mid}"
               . '        AND '
               . '        ( tag_itemid NOT IN '
               . "            ( SELECT DISTINCT {$itemHandler->keyName} "
               . "                FROM {$itemHandler->table} "
               . "                WHERE {$itemHandler->table}.approved > 0"
               . '            ) '
               . '        )';
    else:
        $sql = "    DELETE {$linkHandler->table} FROM {$linkHandler->table}"
               . "    LEFT JOIN {$itemHandler->table} AS aa ON {$linkHandler->table}.tag_itemid = aa.{$itemHandler->keyName} "
               . '    WHERE '
               . "        tag_modid = {$mid}"
               . '        AND '
               . "        ( aa.{$itemHandler->keyName} IS NULL"
               . '            OR aa.approved < 1'
               . '        )';
    endif;
    if (!$result = $linkHandler->db->queryF($sql)) {
        //xoops_error($linkHandler->db->error());
    }
}
