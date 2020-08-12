<?php

use XoopsModules\Myalbum\{
    Helper
};

/** @var Helper $helper */

/**
 * @param $keywords
 * @param $andor
 * @param $limit
 * @param $offset
 * @param $userid
 *
 * @return array
 */
function myalbum_search($keywords, $andor, $limit, $offset, $userid)
{
    global $xoopsDB;
    $helper = Helper::getInstance();
    $moduleDirName = $helper->getDirname();
    require_once $helper->path( 'include/read_configs.php');

    $sql = 'SELECT l.lid,l.cid,l.title,l.submitter,l.date,t.description FROM ' . $xoopsDB->prefix($moduleDirName . '_photos') . ' l LEFT JOIN ' . $xoopsDB->prefix($moduleDirName . '_text') . ' t ON t.lid=l.lid WHERE status>0';

    if ($userid > 0) {
        $sql .= ' AND l.submitter=' . $userid . ' ';
    }

    $whr = '';
    if ($keywords && is_array($keywords)) {
        $whr = 'AND (';
        switch (mb_strtolower($andor)) {
            case 'and':
                foreach ($keywords as $keyword) {
                    $whr .= "CONCAT(l.title,\' \',t.description) LIKE \'%$keyword%\' AND ";
                }
                $whr = mb_substr($whr, 0, -5);
                break;
            case 'or':
                foreach ($keywords as $keyword) {
                    $whr .= "CONCAT(l.title,\' \',t.description) LIKE \'%$keyword%\' OR ";
                }
                $whr = mb_substr($whr, 0, -4);
                break;
            default:
                $whr .= "CONCAT(l.title,\'  \',t.description) LIKE \'%{$keywords[0]}%\'";
                break;
        }
        $whr .= ')';
    }

    $sql    = "$sql $whr ORDER BY l.date DESC";
    $result = $xoopsDB->query($sql, $limit, $offset);
    $ret    = [];
    while (false !== ($myrow = $xoopsDB->fetchArray($result))) {
        $ret[] = [
            'image' => 'assets/images/pict.gif',
            'link'  => 'photo.php?lid=' . $myrow['lid'],
            'title' => $myrow['title'],
            'time'  => $myrow['date'],
            'uid'   => $myrow['submitter'],
        ];
    }

    return $ret;
}

//' ) ;
