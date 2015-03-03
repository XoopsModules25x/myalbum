<?php
// ------------------------------------------------------------------------- //
//                      myAlbum-P - XOOPS photo album                        //
//                        <http://www.peak.ne.jp/>                           //
// ------------------------------------------------------------------------- //

// constants
define('PIPEID_GD', 0);
define('PIPEID_IMAGICK', 1);
define('PIPEID_NETPBM', 2);

include_once 'myalbum.forms.php';
/*
function myalbum_adminMenu ($page, $currentoption = 0)
{
    $GLOBALS['mydirname'] = basename( dirname( dirname( __FILE__ ) ) ) ;
    $module_handler = xoops_gethandler('module');
    $GLOBALS['myalbumModule'] = $module_handler->getByDirname($GLOBALS['mydirname']);
      // Nice buttons styles
    echo "
        <style type='text/css'>
        #form {float:left; width:100%; background: #e7e7e7 url('" . XOOPS_URL . "/modules/".$GLOBALS['myalbumModule']->getVar('dirname')."/images/bg.gif') repeat-x left bottom; font-size:93%; line-height:normal; border-bottom: 1px solid black; border-top: 1px solid black; border-left: 1px solid black; border-right: 1px solid black;}
             #buttontop { float:left; width:100%; background: #e7e7e7; font-size:93%; line-height:normal; border-top: 1px solid black; border-left: 1px solid black; border-right: 1px solid black; margin: 0; }
        #buttonbar { float:left; width:100%; background: #e7e7e7 url('" . XOOPS_URL . "/modules/".$GLOBALS['myalbumModule']->getVar('dirname')."/images/bg.gif') repeat-x left bottom; font-size:93%; line-height:normal; border-left: 1px solid black; border-right: 1px solid black; margin-bottom: 0px; border-bottom: 1px solid black; }
        #buttonbar ul { margin:0; margin-top: 15px; padding:10px 10px 0; list-style:none; }
          #buttonbar li { display:inline; margin:0; padding:0; }
          #buttonbar a { float:left; background:url('" . XOOPS_URL . "/modules/".$GLOBALS['myalbumModule']->getVar('dirname')."/images/left_both.gif') no-repeat left top; margin:0; padding:0 0 0 9px;  text-decoration:none; }
          #buttonbar a span { float:left; display:block; background:url('" . XOOPS_URL . "/modules/".$GLOBALS['myalbumModule']->getVar('dirname')."/images/right_both.gif') no-repeat right top; padding:5px 15px 4px 6px; font-weight:bold; color:#765; }
          // Commented Backslash Hack hides rule from IE5-Mac \
          #buttonbar a span {float:none;}
          // End IE5-Mac hack
          #buttonbar a:hover span { color:#333; }
          #buttonbar #current a { background-position:0 -150px; border-width:0; }
          #buttonbar #current a span { background-position:100% -150px; padding-bottom:5px; color:#333; }
          #buttonbar a:hover { background-position:0% -150px; }
          #buttonbar a:hover span { background-position:100% -150px; }
          </style>";

    $GLOBALS['myts'] = &MyTextSanitizer::getInstance();
    $tblColors = Array();
    if (file_exists(XOOPS_ROOT_PATH . '/modules/'.$GLOBALS['myalbumModule']->getVar('dirname').'/language/' . $GLOBALS['xoopsConfig']['language'] . '/modinfo.php')) {
        include_once XOOPS_ROOT_PATH . '/modules/'.$GLOBALS['myalbumModule']->getVar('dirname').'/language/' . $GLOBALS['xoopsConfig']['language'] . '/modinfo.php';
    } else {
        include_once XOOPS_ROOT_PATH . '/modules/'.$GLOBALS['myalbumModule']->getVar('dirname').'/language/english/modinfo.php';
    }
    echo "<div id='buttontop'>";
    echo "<table style=\"width: 100%; padding: 0; \" cellspacing=\"0\"><tr>";
    echo "<td style=\"width: 45%; font-size: 10px; text-align: left; color: #2F5376; padding: 0 6px; line-height: 18px;\"><a class=\"nobutton\" href=\"".XOOPS_URL."/modules/system/admin.php?fct=preferences&amp;op=showmod&amp;mod=" . $GLOBALS['myalbumModule']->getVar('mid') . "\">" . _PREFERENCES . "</a></td>";
    echo "<td style='font-size: 10px; text-align: right; color: #2F5376; padding: 0 6px; line-height: 18px;'><b>" . $GLOBALS['myts']->displayTarea($GLOBALS['myalbumModule']->name()) ."</td>";
    echo "</tr></table>";
    echo "</div>";
    echo "<div id='buttonbar'>";
    echo "<ul>";
    foreach ($GLOBALS['myalbumModule']->getAdminMenu() as $key => $value) {
        $tblColors[$key] = '';
        $tblColors[$currentoption] = 'current';
          echo "<li id='" . $tblColors[$key] . "'><a href=\"" . XOOPS_URL . "/modules/".$GLOBALS['myalbumModule']->getVar('dirname')."/".$value['link']."\"><span>" . $value['title'] . "</span></a></li>";
    }

    echo "</ul></div>";
    echo "<div id='navigation' style=\"clear:both;height:48px;\">";
    $indexAdmin = new ModuleAdmin();
    echo $indexAdmin->addNavigation($page);
    echo "</div>";
}
*/
/*
function myalbum_footer_adminMenu()
{
    echo "<div align=\"center\"><a href=\"http://www.xoops.org\" target=\"_blank\"><img src=" . XOOPS_URL . '/' . $GLOBALS['myalbumModule']->getInfo('icons32') . '/xoopsmicrobutton.gif'.' '." alt='XOOPS' title='XOOPS'></a></div>";
    echo "<div class='center smallsmall italic pad5'><strong>" . $GLOBALS['myalbumModule']->getVar("name") . "</strong> is maintained by the <a class='tooltip' rel='external' href='http://www.xoops.org/' title='Visit XOOPS Community'>XOOPS Community</a> and <a class='tooltip' rel='external' href='http://www.chronolabs.coop/' title='Visit Chronolabs Co-op'>Chronolabs Co-op</a></div>";
}
*/

function mysql_get_sql_set($cols)
{

    $ret = "";

    foreach ($cols as $col => $types) {

        list($field, $lang, $essential) = explode(':', $types);

        // Undefined col is regarded as ''
        $data = empty($_POST[$col]) ? '' : $GLOBALS['myts']->stripSlashesGPC($_POST[$col]);

        // Check if essential
        if ($essential && !$data) {
            die(sprintf("Error: %s is not set", $col));
        }

        // Language
        switch ($lang) {
            case 'N' : // Number (remove ,)
                $data = str_replace(",", "", $data);
                break;
            case 'J' : // Japanese
                $data = mb_convert_kana($data, "KV");
                break;
            case 'E' : // English
                // $data = mb_convert_kana( $data , "as" ) ;
                $data = $data;
                break;
        }

        // DataType
        switch ($field) {
            case 'A' : // textarea
                $data = addslashes($data);
                $ret .= "$col='$data',";
                break;
            case 'I' : // integer
                $data = intval($data);
                $ret .= "$col='$data',";
                break;
            case 'F' : // float
                $data = doubleval($data);
                $ret .= "$col='$data',";
                break;
            default : // varchar (default)
                if ($field < 1) {
                    $field = 255;
                }
                if (function_exists('mb_strcut')) {
                    $data = mb_strcut($data, 0, $field);
                }
                $data = addslashes($data);
                $ret .= "$col='$data',";
        }
    }

    // Remove ',' in the tale of sql
    $ret = substr($ret, 0, -1);

    return $ret;
}

function myalbum_get_thumbnail_wh($width, $height)
{
    global $myalbum_thumbsize, $myalbum_thumbrule;

    switch ($myalbum_thumbrule) {
        case 'w' :
            $new_w = $myalbum_thumbsize;
            $scale = $width / $new_w;
            $new_h = intval(round($height / $scale));
            break;
        case 'h' :
            $new_h = $myalbum_thumbsize;
            $scale = $height / $new_h;
            $new_w = intval(round($width / $scale));
            break;
        case 'b' :
            if ($width > $height) {
                $new_w = $myalbum_thumbsize;
                $scale = $width / $new_w;
                $new_h = intval(round($height / $scale));
            } else {
                $new_h = $myalbum_thumbsize;
                $scale = $height / $new_h;
                $new_w = intval(round($width / $scale));
            }
            break;
        default :
            $new_w = $myalbum_thumbsize;
            $new_h = $myalbum_thumbsize;
            break;
    }

    return array($new_w, $new_h);
}

// create_thumb Wrapper
// return value
//   0 : read fault
//   1 : complete created
//   2 : copied
//   3 : skipped
//   4 : icon gif (not normal exts)
function myalbum_create_thumb($src_path, $node, $ext)
{
    global $myalbum_imagingpipe, $myalbum_makethumb, $myalbum_normal_exts;

    if (!in_array(strtolower($ext), $myalbum_normal_exts)) {
        return myalbum_copy_thumbs_from_icons($src_path, $node, $ext);
    }

    if ($myalbum_imagingpipe == PIPEID_IMAGICK) {
        return myalbum_create_thumbs_by_imagick($src_path, $node, $ext);
    } elseif ($myalbum_imagingpipe == PIPEID_NETPBM) {
        return myalbum_create_thumbs_by_netpbm($src_path, $node, $ext);
    } else {
        return myalbum_create_thumbs_by_gd($src_path, $node, $ext);
    }
}

// Copy Thumbnail from directory of icons
function myalbum_copy_thumbs_from_icons($src_path, $node, $ext)
{
    global $mod_path, $thumbs_dir;

    @unlink("$thumbs_dir/$node.gif");
    if (file_exists("$mod_path/icons/$ext.gif")) {
        $copy_success = copy("$mod_path/icons/$ext.gif", "$thumbs_dir/$node.gif");
    }
    if (empty($copy_success)) {
        @copy("$mod_path/icons/default.gif", "$thumbs_dir/$node.gif");
    }

    return 4;
}

// Creating Thumbnail by GD
function myalbum_create_thumbs_by_gd($src_path, $node, $ext)
{
    global $myalbum_forcegd2, $thumbs_dir;
    echo __LINE__ . '<br/>';
    $bundled_2 = false;
    if (!$myalbum_forcegd2 && function_exists('gd_info')) {
        $gd_info = gd_info();
        if (substr($gd_info['GD Version'], 0, 10) == 'bundled (2') {
            $bundled_2 = true;
        }
    }
    echo __LINE__ . '<br/>';
    if (!is_readable($src_path)) {
        return 0;
    }
    @unlink("$thumbs_dir/$node.$ext");
    list($width, $height, $type) = getimagesize($src_path);
    switch ($type) {
        case 1 :
            // GIF (skip)
            @copy($src_path, "$thumbs_dir/$node.$ext");

            return 2;
        case 2 :
            // JPEG
            $src_img = imagecreatefromjpeg($src_path);
            break;
        case 3 :
            // PNG
            $src_img = imagecreatefrompng($src_path);
            break;
        default :
            @copy($src_path, "$thumbs_dir/$node.$ext");

            return 2;
    }
    echo __LINE__ . '<br/>';
    list($new_w, $new_h) = myalbum_get_thumbnail_wh($width, $height);
    echo __LINE__ . '<br/>';
    if ($width <= $new_w && $height <= $new_h) {
        // only copy when small enough
        copy($src_path, "$thumbs_dir/$node.$ext");

        return 2;
    }
    echo __LINE__ . '<br/>';
    if (!$bundled_2) {
        $dst_img = imagecreate($new_w, $new_h);
        imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $new_w, $new_h, $width, $height);
    } else {
        $dst_img = @imagecreatetruecolor($new_w, $new_h);
        if (!$dst_img) {
            $dst_img = imagecreate($new_w, $new_h);
            imagecopyresized($dst_img, $src_img, 0, 0, 0, 0, $new_w, $new_h, $width, $height);
        } else {
            imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $new_w, $new_h, $width, $height);
        }
    }
    echo __LINE__ . '<br/>';
    switch ($type) {
        case 2 :
            // JPEG
            imagejpeg($dst_img, "$thumbs_dir/$node.$ext");
            imagedestroy($dst_img);
            break;
        case 3 :
            // PNG
            imagepng($dst_img, "$thumbs_dir/$node.$ext");
            imagedestroy($dst_img);
            break;
    }
    echo __LINE__ . '<br/>';
    imagedestroy($src_img);

    return 1;
}

// Creating Thumbnail by ImageMagick
function myalbum_create_thumbs_by_imagick($src_path, $node, $ext)
{
    global $myalbum_imagickpath, $thumbs_dir;

    // Check the path to binaries of imaging packages
    if (trim($myalbum_imagickpath) != '' && substr($myalbum_imagickpath, -1) != DIRECTORY_SEPARATOR) {
        $myalbum_imagickpath .= DIRECTORY_SEPARATOR;
    }

    if (!is_readable($src_path)) {
        return 0;
    }
    @unlink("$thumbs_dir/$node.$ext");
    list($width, $height, $type) = getimagesize($src_path);

    list($new_w, $new_h) = myalbum_get_thumbnail_wh($width, $height);

    if ($width <= $new_w && $height <= $new_h) {
        // only copy when small enough
        copy($src_path, "$thumbs_dir/$node.$ext");

        return 2;
    }

    // Make Thumb and check success
    exec("{$myalbum_imagickpath}convert -geometry {$new_w}x{$new_h} $src_path $thumbs_dir/$node.$ext");
    if (!is_readable("$thumbs_dir/$node.$ext")) {
        // can't exec convert, big thumbs!
        copy($src_path, "$thumbs_dir/$node.$ext");

        return 2;
    }

    return 1;
}

// Creating Thumbnail by NetPBM
function myalbum_create_thumbs_by_netpbm($src_path, $node, $ext)
{
    global $myalbum_netpbmpath, $thumbs_dir;

    // Check the path to binaries of imaging packages
    if (trim($myalbum_netpbmpath) != '' && substr($myalbum_netpbmpath, -1) != DIRECTORY_SEPARATOR) {
        $myalbum_netpbmpath .= DIRECTORY_SEPARATOR;
    }

    if (!is_readable($src_path)) {
        return 0;
    }
    @unlink("$thumbs_dir/$node.$ext");
    list($width, $height, $type) = getimagesize($src_path);
    switch ($type) {
        case 1 :
            // GIF
            $pipe0 = "{$myalbum_netpbmpath}giftopnm";
            $pipe2 = "{$myalbum_netpbmpath}ppmquant 256 | {$myalbum_netpbmpath}ppmtogif";
            break;
        case 2 :
            // JPEG
            $pipe0 = "{$myalbum_netpbmpath}jpegtopnm";
            $pipe2 = "{$myalbum_netpbmpath}pnmtojpeg";
            break;
        case 3 :
            // PNG
            $pipe0 = "{$myalbum_netpbmpath}pngtopnm";
            $pipe2 = "{$myalbum_netpbmpath}pnmtopng";
            break;
        default :
            @copy($src_path, "$thumbs_dir/$node.$ext");

            return 2;
    }

    list($new_w, $new_h) = myalbum_get_thumbnail_wh($width, $height);

    if ($width <= $new_w && $height <= $new_h) {
        // only copy when small enough
        copy($src_path, "$thumbs_dir/$node.$ext");

        return 2;
    }

    $pipe1 = "{$myalbum_netpbmpath}pnmscale -xysize $new_w $new_h";

    // Make Thumb and check success
    exec("$pipe0 < $src_path | $pipe1 | $pipe2 > $thumbs_dir/$node.$ext");
    if (!is_readable("$thumbs_dir/$node.$ext")) {
        // can't exec convert, big thumbs!
        copy($src_path, "$thumbs_dir/$node.$ext");

        return 2;
    }

    return 1;
}

// modifyPhoto Wrapper
function myalbum_modify_photo($src_path, $dst_path)
{
    global $myalbum_imagingpipe, $myalbum_forcegd2, $myalbum_normal_exts;

    $ext = substr(strrchr($dst_path, '.'), 1);

    if (!in_array(strtolower($ext), $myalbum_normal_exts)) {
        rename($src_path, $dst_path);
    }

    if ($myalbum_imagingpipe == PIPEID_IMAGICK) {
        myalbum_modify_photo_by_imagick($src_path, $dst_path);
    } elseif ($myalbum_imagingpipe == PIPEID_NETPBM) {
        myalbum_modify_photo_by_netpbm($src_path, $dst_path);
    } else {
        if ($myalbum_forcegd2) {
            myalbum_modify_photo_by_gd($src_path, $dst_path);
        } else {
            rename($src_path, $dst_path);
        }
    }
}

// Modifying Original Photo by GD
function myalbum_modify_photo_by_gd($src_path, $dst_path)
{
    global $myalbum_width, $myalbum_height;

    if (!is_readable($src_path)) {
        return 0;
    }

    list($width, $height, $type) = getimagesize($src_path);

    switch ($type) {
        case 1 :
            // GIF
            @rename($src_path, $dst_path);

            return 2;
        case 2 :
            // JPEG
            $src_img = imagecreatefromjpeg($src_path);
            break;
        case 3 :
            // PNG
            $src_img = imagecreatefrompng($src_path);
            break;
        default :
            @rename($src_path, $dst_path);

            return 2;
    }

    if ($width > $myalbum_width || $height > $myalbum_height) {
        if ($width / $myalbum_width > $height / $myalbum_height) {
            $new_w = $myalbum_width;
            $scale = $width / $new_w;
            $new_h = intval(round($height / $scale));
        } else {
            $new_h = $myalbum_height;
            $scale = $height / $new_h;
            $new_w = intval(round($width / $scale));
        }
        $dst_img = imagecreatetruecolor($new_w, $new_h);
        imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $new_w, $new_h, $width, $height);
    }

    if (isset($_POST['rotate']) && function_exists('imagerotate')) {
        switch ($_POST['rotate']) {
            case 'rot270' :
                if (!isset($dst_img) || !is_resource($dst_img)) {
                    $dst_img = $src_img;
                }
                // patch for 4.3.1 bug
                $dst_img = imagerotate($dst_img, 270, 0);
                $dst_img = imagerotate($dst_img, 180, 0);
                break;
            case 'rot180' :
                if (!isset($dst_img) || !is_resource($dst_img)) {
                    $dst_img = $src_img;
                }
                $dst_img = imagerotate($dst_img, 180, 0);
                break;
            case 'rot90' :
                if (!isset($dst_img) || !is_resource($dst_img)) {
                    $dst_img = $src_img;
                }
                $dst_img = imagerotate($dst_img, 270, 0);
                break;
            default :
            case 'rot0' :
                break;
        }
    }

    if (isset($dst_img) && is_resource($dst_img)) {
        switch ($type) {
            case 2 :
                // JPEG
                imagejpeg($dst_img, $dst_path);
                imagedestroy($dst_img);
                break;
            case 3 :
                // PNG
                imagepng($dst_img, $dst_path);
                imagedestroy($dst_img);
                break;
        }
    }

    imagedestroy($src_img);
    if (!is_readable($dst_path)) {
        // didn't exec convert, rename it.
        @rename($src_path, $dst_path);

        return 2;
    } else {
        @unlink($src_path);

        return 1;
    }
}

// Modifying Original Photo by ImageMagick
function myalbum_modify_photo_by_imagick($src_path, $dst_path)
{
    global $myalbum_width, $myalbum_height, $myalbum_imagickpath;

    // Check the path to binaries of imaging packages
    if (trim($myalbum_imagickpath) != '' && substr($myalbum_imagickpath, -1) != DIRECTORY_SEPARATOR) {
        $myalbum_imagickpath .= DIRECTORY_SEPARATOR;
    }

    if (!is_readable($src_path)) {
        return 0;
    }

    // Make options for imagick
    $option      = "";
    $image_stats = getimagesize($src_path);
    if ($image_stats[0] > $myalbum_width || $image_stats[1] > $myalbum_height) {
        $option .= " -geometry {$myalbum_width}x{$myalbum_height}";
    }
    if (isset($_POST['rotate'])) {
        switch ($_POST['rotate']) {
            case 'rot270' :
                $option .= " -rotate 270";
                break;
            case 'rot180' :
                $option .= " -rotate 180";
                break;
            case 'rot90' :
                $option .= " -rotate 90";
                break;
            default :
            case 'rot0' :
                break;
        }
    }

    // Do Modify and check success
    if ($option != "") {
        exec("{$myalbum_imagickpath}convert $option $src_path $dst_path");
    }

    if (!is_readable($dst_path)) {
        // didn't exec convert, rename it.
        @rename($src_path, $dst_path);

        return 2;
    } else {
        @unlink($src_path);

        return 1;
    }
}

// Modifying Original Photo by NetPBM
function myalbum_modify_photo_by_netpbm($src_path, $dst_path)
{
    global $myalbum_width, $myalbum_height, $myalbum_netpbmpath;

    // Check the path to binaries of imaging packages
    if (trim($myalbum_netpbmpath) != '' && substr($myalbum_netpbmpath, -1) != DIRECTORY_SEPARATOR) {
        $myalbum_netpbmpath .= DIRECTORY_SEPARATOR;
    }

    if (!is_readable($src_path)) {
        return 0;
    }

    list($width, $height, $type) = getimagesize($src_path);

    $pipe1 = '';
    switch ($type) {
        case 1 :
            // GIF
            $pipe0 = "{$myalbum_netpbmpath}giftopnm";
            $pipe2 = "{$myalbum_netpbmpath}ppmquant 256 | {$myalbum_netpbmpath}ppmtogif";
            break;
        case 2 :
            // JPEG
            $pipe0 = "{$myalbum_netpbmpath}jpegtopnm";
            $pipe2 = "{$myalbum_netpbmpath}pnmtojpeg";
            break;
        case 3 :
            // PNG
            $pipe0 = "{$myalbum_netpbmpath}pngtopnm";
            $pipe2 = "{$myalbum_netpbmpath}pnmtopng";
            break;
        default :
            @rename($src_path, $dst_path);

            return 2;
    }

    if ($width > $myalbum_width || $height > $myalbum_height) {
        if ($width / $myalbum_width > $height / $myalbum_height) {
            $new_w = $myalbum_width;
            $scale = $width / $new_w;
            $new_h = intval(round($height / $scale));
        } else {
            $new_h = $myalbum_height;
            $scale = $height / $new_h;
            $new_w = intval(round($width / $scale));
        }
        $pipe1 .= "{$myalbum_netpbmpath}pnmscale -xysize $new_w $new_h |";
    }

    if (isset($_POST['rotate'])) {
        switch ($_POST['rotate']) {
            case 'rot270' :
                $pipe1 .= "{$myalbum_netpbmpath}pnmflip -r90 |";
                break;
            case 'rot180' :
                $pipe1 .= "{$myalbum_netpbmpath}pnmflip -r180 |";
                break;
            case 'rot90' :
                $pipe1 .= "{$myalbum_netpbmpath}pnmflip -r270 |";
                break;
            default :
            case 'rot0' :
                break;
        }
    }

    // Do Modify and check success
    if ($pipe1) {
        $pipe1 = substr($pipe1, 0, -1);
        exec("$pipe0 < $src_path | $pipe1 | $pipe2 > $dst_path");
    }

    if (!is_readable($dst_path)) {
        // didn't exec convert, rename it.
        @rename($src_path, $dst_path);

        return 2;
    } else {
        @unlink($src_path);

        return 1;
    }
}

// Clear templorary files
function myalbum_clear_tmp_files($dir_path, $prefix = 'tmp_')
{
    // return if directory can't be opened
    if (!($dir = @opendir($dir_path))) {
        return 0;
    }

    $ret        = 0;
    $prefix_len = strlen($prefix);
    while (($file = readdir($dir)) !== false) {
        if (strncmp($file, $prefix, $prefix_len) === 0) {
            if (@unlink("$dir_path/$file")) {
                $ret++;
            }
        }
    }
    closedir($dir);

    return $ret;
}

//updates rating data in itemtable for a given item
function myalbum_updaterating($lid)
{
    $votedata_handler = xoops_getmodulehandler('votedata', $GLOBALS['mydirname']);
    $criteria         = new CriteriaCompo(new Criteria('`lid`', $lid));
    $votes            = $votedata_handler->getObjects($criteria, true);
    $votesDB          = $votedata_handler->getCount($criteria);
    $totalrating      = 0;
    foreach ($votes as $vid => $vote) {
        $totalrating += $vote->getVar('rating');
    }
    if ($votesDB > 0) {
        $finalrating = number_format($totalrating / $votesDB, 4);
    } else {
        $finalrating = 0;
    }
    $photos_handler = xoops_getmodulehandler('photos', $GLOBALS['mydirname']);
    $photo          = $photos_handler->get($lid);
    $photo->setVar('rating', $finalrating);
    $photos_handler->insert($photo, true) or die("Error: DB update rating.");
}

// Returns the number of photos included in a Category
function myalbum_get_photo_small_sum_from_cat($cid, $criteria = null)
{
    if (is_object($criteria)) {
        $criteria = new CriteriaCompo($criteria);
    }
    $criteria->add(new Criteria('`cid`', $cid));
    $photo_handler = xoops_getmodulehandler('photos', $GLOBALS['mydirname']);

    return $photo_handler->getCount($criteria);
}

// Returns the number of whole photos included in a Category
function myalbum_get_photo_total_sum_from_cats($cids, $criteria = null)
{
    if (is_object($criteria)) {
        $criteria = new CriteriaCompo($criteria);
    }
    $criteria->add(new Criteria('`cid`', '(' . implode(',', $cids) . ',0)', "IN"));
    $photo_handler = xoops_getmodulehandler('photos', $GLOBALS['mydirname']);

    return $photo_handler->getCount($criteria);
}

// Update a photo
function myalbum_update_photo($lid, $cid, $title, $desc, $valid = null, $ext = "", $x = "", $y = "")
{
    $cat_handler    = xoops_getmodulehandler('cat', $GLOBALS['mydirname']);
    $photos_handler = xoops_getmodulehandler('photos', $GLOBALS['mydirname']);
    $text_handler   = xoops_getmodulehandler('text', $GLOBALS['mydirname']);
    $photo          = $photos_handler->get($lid);
    $text           = $text_handler->get($lid);
    $cat            = $cat_handler->get($cid);

    if (isset($valid)) {
        $photo->setVar('status', $valid);
        // Trigger Notification
        if ($valid == 1) {
            $notification_handler =& xoops_gethandler('notification');

            // Global Notification
            $notification_handler->triggerEvent('global', 0, 'new_photo', array('PHOTO_TITLE' => $title, 'PHOTO_URI' => $photo->getURL()));

            // Category Notification

            $cat_title = $cat->getVar('title');
            $notification_handler->triggerEvent(
                'category',
                $cid,
                'new_photo',
                array('PHOTO_TITLE' => $title, 'CATEGORY_TITLE' => $cat_title, 'PHOTO_URI' => $photo->getURL())
            );
        }
    }

    $photo->setVar('cid', $cid);
    $photo->setVar('title', $title);

    if ($ext != '') {
        $photo->setVar('ext', $ext);
    }
    if ($x != '') {
        $photo->setVar('res_x', $x);
    }
    if ($y != '') {
        $photo->setVar('res_y', $y);
    }

    $cid = empty($_POST['cid']) ? 0 : intval($_POST['cid']);

    if ($photos_handler->insert($photo, true)) {
        $text->setVar('description', $desc);
        @$text_handler->insert($text, true);
    }

    // not admin can only touch photos status>0
    redirect_header($photo->getEditURL(), 0, _ALBM_DBUPDATED);
}

// Delete photos hit by the $whr clause
function myalbum_delete_photos($criteria = null)
{
    $photos_handler = xoops_getmodulehandler('photos', $GLOBALS['mydirname']);
    $photos         = $photos_handler->getObjects($criteria);
    foreach ($photos as $lid => $photo) {
        $photos_handler->delete($photo);
    }
}

// Substitution of opentable()
function myalbum_opentable()
{
    echo "<div style='border: 2px solid #2F5376;padding:8px;width:95%;' class='bg4'>\n";
}

// Substitution of closetable()
function myalbum_closetable()
{
    echo "</div>\n";
}

// returns extracted string for options from table with xoops tree
function myalbum_get_cat_options($order = 'title', $preset = 0, $prefix = '--', $none = null, $table_name_cat = null, $table_name_photos = null)
{
    if (empty($table_name_cat)) {
        $table_name_cat = $GLOBALS['xoopsDB']->prefix($GLOBALS['table_cat']);
    }
    if (empty($table_name_photos)) {
        $table_name_photos = $GLOBALS['xoopsDB']->prefix($GLOBALS['table_photos']);
    }

    $cats[0] = array('cid' => 0, 'pid' => -1, 'next_key' => -1, 'depth' => 0, 'title' => '', 'num' => 0);

    $rs = $GLOBALS['xoopsDB']->query(
        "SELECT c.title,c.cid,c.pid,COUNT(p.lid) AS num FROM $table_name_cat c LEFT JOIN $table_name_photos p ON c.cid=p.cid GROUP BY c.cid ORDER BY pid ASC,$order DESC"
    );

    $key = 1;
    while (list($title, $cid, $pid, $num) = $GLOBALS['xoopsDB']->fetchRow($rs)) {
        $cats[$key] = array(
            'cid'      => intval($cid),
            'pid'      => intval($pid),
            'next_key' => $key + 1,
            'depth'    => 0,
            'title'    => $GLOBALS['myts']->htmlSpecialChars($title),
            'num'      => intval($num)
        );
        $key++;
    }
    $sizeofcats = $key;

    $loop_check_for_key = 1024;
    for ($key = 1; $key < $sizeofcats; $key++) {
        $cat    =& $cats[$key];
        $target =& $cats[0];
        if (--$loop_check_for_key < 0) {
            $loop_check = -1;
        } else {
            $loop_check = 4096;
        }

        while (1) {
            if ($cat['pid'] == $target['cid']) {
                $cat['depth']       = $target['depth'] + 1;
                $cat['next_key']    = $target['next_key'];
                $target['next_key'] = $key;
                break;
            } elseif (--$loop_check < 0) {
                $cat['depth']       = 1;
                $cat['next_key']    = $target['next_key'];
                $target['next_key'] = $key;
                break;
            } elseif ($target['next_key'] < 0) {
                $cat_backup = $cat;
                array_splice($cats, $key, 1);
                array_push($cats, $cat_backup);
                --$key;
                break;
            }
            $target =& $cats[$target['next_key']];
        }
    }

    if (isset($none)) {
        $ret = "<option value=''>$none</option>\n";
    } else {
        $ret = '';
    }
    $cat =& $cats[0];
    for ($weight = 1; $weight < $sizeofcats; $weight++) {
        $cat      =& $cats[$cat['next_key']];
        $pref     = str_repeat($prefix, $cat['depth'] - 1);
        $selected = $preset == $cat['cid'] ? "selected='selected'" : '';
        $ret .= "<option value='{$cat['cid']}' $selected>$pref {$cat['title']} ({$cat['num']})</option>\n";
    }

    return $ret;
}

function extractSummary($html)
{
    $html = $GLOBALS['myts']->displayTarea($html, 1, 1, 1, 1, 1, 1, 1);
    $ret  = '';
    $i    = 0;
    if ($html != "") {
        if ($i < 4) {
            foreach (explode('.', strip_tags($html)) as $raw) {
                if ($i < 4) {
                    foreach (explode('!', strip_tags($raw)) as $rawb) {
                        if ($i < 4) {
                            foreach (explode('?', strip_tags($rawb)) as $rawc) {
                                if (!strpos(' ' . $ret, $rawc)) {
                                    ++$i;
                                    if ($i < 4) {
                                        $ret .= $rawc . '. ';
                                    } else {
                                        continue;
                                    }
                                } else {
                                    continue;
                                }
                            }
                        } else {
                            continue;
                        }
                    }
                } else {
                    continue;
                }
            }
        } else {
            continue;
        }
    }

    return trim($ret);
}
