<?php

if (!defined('XOOPS_ROOT_PATH')) {
    exit;
}

function myalbum_search($keywords, $andor, $limit, $offset, $userid)
{
    global $xoopsDB;
    $mydirname = basename(dirname(dirname(__FILE__)));
    include(XOOPS_ROOT_PATH . '/modules/' . $mydirname . '/include/read_configs.php');

    $sql = "SELECT l.lid,l.cid,l.title,l.submitter,l.date,t.description FROM " . $xoopsDB->prefix($mydirname . '_photos') . " l LEFT JOIN "
        . $xoopsDB->prefix($mydirname . '_text') . " t ON t.lid=l.lid WHERE status>0";

    if ($userid > 0) {
        $sql .= " AND l.submitter=" . $userid . " ";
    }

    $whr = "";
    if (is_array($keywords) && count($keywords) > 0) {
        $whr = "AND (";
        switch (strtolower($andor)) {
            case "and" :
                foreach ($keywords as $keyword) {
                    $whr .= "CONCAT(l.title,\' \',t.description) LIKE \'%$keyword%\' AND ";
                }
                $whr = substr($whr, 0, -5);
                break;
            case "or" :
                foreach ($keywords as $keyword) {
                    $whr .= "CONCAT(l.title,\' \',t.description) LIKE \'%$keyword%\' OR ";
                }
                $whr = substr($whr, 0, -4);
                break;
            default :
                $whr .= "CONCAT(l.title,\'  \',t.description) LIKE \'%{$keywords[0]}%\'";
                break;
        }
        $whr .= ")";
    }

    $sql = "$sql $whr ORDER BY l.date DESC";
    $result = $xoopsDB->query($sql, $limit, $offset);
    $ret = array();
    while ($myrow = $xoopsDB->fetchArray($result)) {
        $ret[] = array(
            "image" => "images/pict.gif",
            "link"  => "photo.php?lid=" . $myrow["lid"],
            "title" => $myrow["title"],
            "time"  => $myrow["date"],
            "uid"   => $myrow["submitter"]
        );
    }

    return $ret;
}

//' ) ;
