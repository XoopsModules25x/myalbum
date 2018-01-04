<?php namespace XoopsModules\Myalbum;

// ------------------------------------------------------------------------- //
//                      myAlbum-P - XOOPS photo album                        //
//                        <http://www.peak.ne.jp>                           //
// ------------------------------------------------------------------------- //

use Xmf\Request;
use XoopsModules\Myalbum;
use XoopsModules\Myalbum\Common;

// constants
define('PIPEID_GD', 0);
define('PIPEID_IMAGICK', 1);
define('PIPEID_NETPBM', 2);

require_once __DIR__ . '/forms.php';


/**
 * Class Utility
 */
class Utility extends \XoopsObject
{
    use common\VersionChecks; //checkVerXoops, checkVerPhp Traits

    use common\ServerStats; // getServerStats Trait

    use common\FilesManagement; // Files Management Trait

    //--------------- Custom module methods -----------------------------

    /**
     * @param $cols
     *
     * @return string
     */
    public static function mysqliGetSqlSet($cols)
    {
        $ret = '';

        foreach ($cols as $col => $types) {
            list($field, $lang, $essential) = explode(':', $types);

            // Undefined col is regarded as ''
            $data = empty($_POST[$col]) ? '' : $GLOBALS['myts']->stripSlashesGPC($_POST[$col]);

            // Check if essential
            if ($essential && !$data) {
                exit(sprintf('Error: %s is not set', $col));
            }

            // Language
            switch ($lang) {
                case 'N': // Number (remove ,)
                    $data = str_replace(',', '', $data);
                    break;
                case 'J': // Japanese
                    $data = mb_convert_kana($data, 'KV');
                    break;
                case 'E': // English
                    // $data = mb_convert_kana( $data , "as" ) ;
                    //                    $data = $data;
                    break;
            }

            // DataType
            switch ($field) {
                case 'A': // textarea
                    $data = addslashes($data);
                    $ret  .= "$col='$data',";
                    break;
                case 'I': // integer
                    $data = (int)$data;
                    $ret  .= "$col='$data',";
                    break;
                case 'F': // float
                    $data = (float)$data;
                    $ret  .= "$col='$data',";
                    break;
                default: // varchar (default)
                    if ($field < 1) {
                        $field = 255;
                    }
                    if (function_exists('mb_strcut')) {
                        $data = mb_strcut($data, 0, $field);
                    }
                    $data = addslashes($data);
                    $ret  .= "$col='$data',";
            }
        }

        // Remove ',' in the tale of sql
        $ret = substr($ret, 0, -1);

        return $ret;
    }

    /**
     * @param $width
     * @param $height
     *
     * @return array
     */
    public static function getThumbWidth($width, $height)
    {
        $moduleDirName = basename(dirname(__DIR__));
        switch ($GLOBALS[$moduleDirName . '_thumbrule']) {
            case 'w':
                $new_w = $GLOBALS[$moduleDirName . '_thumbsize'];
                $scale = $width / $new_w;
                $new_h = (int)round($height / $scale);
                break;
            case 'h':
                $new_h = $GLOBALS[$moduleDirName . '_thumbsize'];
                $scale = $height / $new_h;
                $new_w = (int)round($width / $scale);
                break;
            case 'b':
                if ($width > $height) {
                    $new_w = $GLOBALS[$moduleDirName . '_thumbsize'];
                    $scale = $width / $new_w;
                    $new_h = (int)round($height / $scale);
                } else {
                    $new_h = $GLOBALS[$moduleDirName . '_thumbsize'];
                    $scale = $height / $new_h;
                    $new_w = (int)round($width / $scale);
                }
                break;
            default:
                $new_w = $GLOBALS[$moduleDirName . '_thumbsize'];
                $new_h = $GLOBALS[$moduleDirName . '_thumbsize'];
                break;
        }

        return [
            $new_w,
            $new_h
        ];
    }

    // create_thumb Wrapper
    // return value
    //   0 : read fault
    //   1 : complete created
    //   2 : copied
    //   3 : skipped
    //   4 : icon gif (not normal exts)
    /**
     * @param $src_path
     * @param $node
     * @param $ext
     *
     * @return int
     */
    public static function createThumb($src_path, $node, $ext)
    {
        $moduleDirName = basename(dirname(__DIR__));
        if (!in_array(strtolower($ext), $GLOBALS[$moduleDirName . '_normal_exts'])) {
            return static::createThumbsFromIcons($src_path, $node, $ext);
        }

        if (PIPEID_IMAGICK == $GLOBALS[$moduleDirName . '_imagingpipe']) {
            return static::createThumbsWithImagick($src_path, $node, $ext);
        } elseif (PIPEID_NETPBM == $GLOBALS[$moduleDirName . '_imagingpipe']) {
            return static::createThumbsWithNetpbm($src_path, $node, $ext);
        } else {
            return static::createThumbsWithGd($src_path, $node, $ext);
        }
    }

    // Copy Thumbnail from directory of icons

    /**
     * @param $src_path
     * @param $node
     * @param $ext
     *
     * @return int
     */
    public static function createThumbsFromIcons($src_path, $node, $ext)
    {
        global $mod_path, $thumbs_dir;

        @unlink("$thumbs_dir/$node.gif");
        if (file_exists("$mod_path/assets/images/icons/$ext.gif")) {
            $copy_success = copy("$mod_path/assets/images/icons/$ext.gif", "$thumbs_dir/$node.gif");
        }
        if (empty($copy_success)) {
            @copy("$mod_path/assets/images/icons/default.gif", "$thumbs_dir/$node.gif");
        }

        return 4;
    }

    // Creating Thumbnail by GD

    /**
     * @param $src_path
     * @param $node
     * @param $ext
     *
     * @return int
     */
    public static function createThumbsWithGd($src_path, $node, $ext)
    {
        global $thumbs_dir;

        $moduleDirName = basename(dirname(__DIR__));
        echo __LINE__ . '<br>';
        $bundled_2 = false;
        if (!$GLOBALS[$moduleDirName . '_forcegd2'] && function_exists('gd_info')) {
            $gd_info = gd_info();
            // if (substr($gd_info['GD Version'], 0, 10) === 'bundled (2') {
            if (0 === strpos($gd_info['GD Version'], 'bundled (2')) {
                $bundled_2 = true;
            }
        }
        echo __LINE__ . '<br>';
        if (!is_readable($src_path)) {
            return 0;
        }
        @unlink("$thumbs_dir/$node.$ext");
        list($width, $height, $type) = getimagesize($src_path);
        switch ($type) {
            case 1:
                // GIF (skip)
                @copy($src_path, "$thumbs_dir/$node.$ext");

                return 2;
            case 2:
                // JPEG
                $src_img = imagecreatefromjpeg($src_path);
                break;
            case 3:
                // PNG
                $src_img = imagecreatefrompng($src_path);
                break;
            default:
                @copy($src_path, "$thumbs_dir/$node.$ext");

                return 2;
        }
        echo __LINE__ . '<br>';
        list($new_w, $new_h) = static::getThumbWidth($width, $height);
        echo __LINE__ . '<br>';
        if ($width <= $new_w && $height <= $new_h) {
            // only copy when small enough
            copy($src_path, "$thumbs_dir/$node.$ext");

            return 2;
        }
        echo __LINE__ . '<br>';
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
        echo __LINE__ . '<br>';
        switch ($type) {
            case 2:
                // JPEG
                imagejpeg($dst_img, "$thumbs_dir/$node.$ext");
                imagedestroy($dst_img);
                break;
            case 3:
                // PNG
                imagepng($dst_img, "$thumbs_dir/$node.$ext");
                imagedestroy($dst_img);
                break;
        }
        echo __LINE__ . '<br>';
        imagedestroy($src_img);

        return 1;
    }

    // Creating Thumbnail by ImageMagick

    /**
     * @param $src_path
     * @param $node
     * @param $ext
     *
     * @return int
     */
    public static function createThumbsWithImagick($src_path, $node, $ext)
    {
        global $thumbs_dir;
        $moduleDirName = basename(dirname(__DIR__));
        // Check the path to binaries of imaging packages
        if ('' != trim($GLOBALS[$moduleDirName . '_imagickpath']) && '/' !== substr($GLOBALS[$moduleDirName . '_imagickpath'], -1)) {
            $GLOBALS[$moduleDirName . '_imagickpath'] .= '/';
        }

        if (!is_readable($src_path)) {
            return 0;
        }
        @unlink("$thumbs_dir/$node.$ext");
        list($width, $height, $type) = getimagesize($src_path);

        list($new_w, $new_h) = static::getThumbWidth($width, $height);

        if ($width <= $new_w && $height <= $new_h) {
            // only copy when small enough
            copy($src_path, "$thumbs_dir/$node.$ext");

            return 2;
        }

        // Make Thumb and check success
        exec("{$GLOBALS[$moduleDirName.'_imagickpath']}convert -geometry {$new_w}x{$new_h} $src_path $thumbs_dir/$node.$ext");
        if (!is_readable("$thumbs_dir/$node.$ext")) {
            // can't exec convert, big thumbs!
            copy($src_path, "$thumbs_dir/$node.$ext");

            return 2;
        }

        return 1;
    }

    // Creating Thumbnail by NetPBM

    /**
     * @param $src_path
     * @param $node
     * @param $ext
     *
     * @return int
     */
    public static function createThumbsWithNetpbm($src_path, $node, $ext)
    {
        global $thumbs_dir;
        $moduleDirName = basename(dirname(__DIR__));
        // Check the path to binaries of imaging packages
        if ('' != trim($GLOBALS[$moduleDirName . '_netpbmpath']) && DIRECTORY_SEPARATOR != substr($GLOBALS[$moduleDirName . '_netpbmpath'], -1)) {
            $GLOBALS[$moduleDirName . '_netpbmpath'] .= DIRECTORY_SEPARATOR;
        }

        if (!is_readable($src_path)) {
            return 0;
        }
        @unlink("$thumbs_dir/$node.$ext");
        list($width, $height, $type) = getimagesize($src_path);
        switch ($type) {
            case 1:
                // GIF
                $pipe0 = "{$GLOBALS[$moduleDirName.'_netpbmpath']}giftopnm";
                $pipe2 = "{$GLOBALS[$moduleDirName.'_netpbmpath']}ppmquant 256 | {$GLOBALS[$moduleDirName.'_netpbmpath']}ppmtogif";
                break;
            case 2:
                // JPEG
                $pipe0 = "{$GLOBALS[$moduleDirName.'_netpbmpath']}jpegtopnm";
                $pipe2 = "{$GLOBALS[$moduleDirName.'_netpbmpath']}pnmtojpeg";
                break;
            case 3:
                // PNG
                $pipe0 = "{$GLOBALS[$moduleDirName.'_netpbmpath']}pngtopnm";
                $pipe2 = "{$GLOBALS[$moduleDirName.'_netpbmpath']}pnmtopng";
                break;
            default:
                @copy($src_path, "$thumbs_dir/$node.$ext");

                return 2;
        }

        list($new_w, $new_h) = static::getThumbWidth($width, $height);

        if ($width <= $new_w && $height <= $new_h) {
            // only copy when small enough
            copy($src_path, "$thumbs_dir/$node.$ext");

            return 2;
        }

        $pipe1 = "{$GLOBALS[$moduleDirName.'_netpbmpath']}pnmscale -xysize $new_w $new_h";

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

    /**
     * @param $src_path
     * @param $dst_path
     */
    public static function editPhoto($src_path, $dst_path)
    {
        $moduleDirName = basename(dirname(__DIR__));
        $ext           = substr(strrchr($dst_path, '.'), 1);

        if (!in_array(strtolower($ext), $GLOBALS[$moduleDirName . '_normal_exts'])) {
            rename($src_path, $dst_path);
        }

        if (PIPEID_IMAGICK == $GLOBALS[$moduleDirName . '_imagingpipe']) {
            static::editPhotoWithImagick($src_path, $dst_path);
        } elseif (PIPEID_NETPBM == $GLOBALS[$moduleDirName . '_imagingpipe']) {
            static::editPhotoWithNetpbm($src_path, $dst_path);
        } else {
            if ($GLOBALS[$moduleDirName . '_forcegd2']) {
                static::editPhotoWithGd($src_path, $dst_path);
            } else {
                rename($src_path, $dst_path);
            }
        }
    }

    // Modifying Original Photo by GD

    /**
     * @param $src_path
     * @param $dst_path
     *
     * @return int
     */
    public static function editPhotoWithGd($src_path, $dst_path)
    {
        if (!is_readable($src_path)) {
            return 0;
        }

        list($width, $height, $type) = getimagesize($src_path);

        switch ($type) {
            case 1:
                // GIF
                @rename($src_path, $dst_path);

                return 2;
            case 2:
                // JPEG
                $src_img = imagecreatefromjpeg($src_path);
                break;
            case 3:
                // PNG
                $src_img = imagecreatefrompng($src_path);
                break;
            default:
                @rename($src_path, $dst_path);

                return 2;
        }

        if ($width > $GLOBALS[$moduleDirName . '_width'] || $height > $GLOBALS[$moduleDirName . '_height']) {
            if ($width / $GLOBALS[$moduleDirName . '_width'] > $height / $GLOBALS[$moduleDirName . '_height']) {
                $new_w = $GLOBALS[$moduleDirName . '_width'];
                $scale = $width / $new_w;
                $new_h = (int)round($height / $scale);
            } else {
                $new_h = $GLOBALS[$moduleDirName . '_height'];
                $scale = $height / $new_h;
                $new_w = (int)round($width / $scale);
            }
            $dst_img = imagecreatetruecolor($new_w, $new_h);
            imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $new_w, $new_h, $width, $height);
        }

        if (isset($_POST['rotate']) && function_exists('imagerotate')) {
            switch ($_POST['rotate']) {
                case 'rot270':
                    if (!isset($dst_img) || !is_resource($dst_img)) {
                        $dst_img = $src_img;
                    }
                    // patch for 4.3.1 bug
                    $dst_img = imagerotate($dst_img, 270, 0);
                    $dst_img = imagerotate($dst_img, 180, 0);
                    break;
                case 'rot180':
                    if (!isset($dst_img) || !is_resource($dst_img)) {
                        $dst_img = $src_img;
                    }
                    $dst_img = imagerotate($dst_img, 180, 0);
                    break;
                case 'rot90':
                    if (!isset($dst_img) || !is_resource($dst_img)) {
                        $dst_img = $src_img;
                    }
                    $dst_img = imagerotate($dst_img, 270, 0);
                    break;
                default:
                case 'rot0':
                    break;
            }
        }

        if (isset($dst_img) && is_resource($dst_img)) {
            switch ($type) {
                case 2:
                    // JPEG
                    imagejpeg($dst_img, $dst_path);
                    imagedestroy($dst_img);
                    break;
                case 3:
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

    /**
     * @param $src_path
     * @param $dst_path
     *
     * @return int
     */
    public static function editPhotoWithImagick($src_path, $dst_path)
    {
        $moduleDirName = basename(dirname(__DIR__));
        // Check the path to binaries of imaging packages
        if ('' != trim($GLOBALS[$moduleDirName . '_imagickpath']) && DIRECTORY_SEPARATOR != substr($GLOBALS[$moduleDirName . '_imagickpath'], -1)) {
            $GLOBALS[$moduleDirName . '_imagickpath'] .= DIRECTORY_SEPARATOR;
        }

        if (!is_readable($src_path)) {
            return 0;
        }

        // Make options for imagick
        $option      = '';
        $image_stats = getimagesize($src_path);
        if ($image_stats[0] > $GLOBALS[$moduleDirName . '_width'] || $image_stats[1] > $GLOBALS[$moduleDirName . '_height']) {
            $option .= " -geometry {$GLOBALS[$moduleDirName.'_width']}x{$GLOBALS[$moduleDirName.'_height']}";
        }
        if (isset($_POST['rotate'])) {
            switch ($_POST['rotate']) {
                case 'rot270':
                    $option .= ' -rotate 270';
                    break;
                case 'rot180':
                    $option .= ' -rotate 180';
                    break;
                case 'rot90':
                    $option .= ' -rotate 90';
                    break;
                default:
                case 'rot0':
                    break;
            }
        }

        // Do Modify and check success
        if ('' != $option) {
            exec("{$GLOBALS[$moduleDirName.'_imagickpath']}convert $option $src_path $dst_path");
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

    /**
     * @param $src_path
     * @param $dst_path
     *
     * @return int
     */
    public static function editPhotoWithNetpbm($src_path, $dst_path)
    {
        $moduleDirName = basename(dirname(__DIR__));
        // Check the path to binaries of imaging packages
        if ('' != trim($GLOBALS[$moduleDirName . '_netpbmpath']) && DIRECTORY_SEPARATOR != substr($GLOBALS[$moduleDirName . '_netpbmpath'], -1)) {
            $GLOBALS[$moduleDirName . '_netpbmpath'] .= DIRECTORY_SEPARATOR;
        }

        if (!is_readable($src_path)) {
            return 0;
        }

        list($width, $height, $type) = getimagesize($src_path);

        $pipe1 = '';
        switch ($type) {
            case 1:
                // GIF
                $pipe0 = "{$GLOBALS[$moduleDirName.'_netpbmpath']}giftopnm";
                $pipe2 = "{$GLOBALS[$moduleDirName.'_netpbmpath']}ppmquant 256 | {$GLOBALS[$moduleDirName.'_netpbmpath']}ppmtogif";
                break;
            case 2:
                // JPEG
                $pipe0 = "{$GLOBALS[$moduleDirName.'_netpbmpath']}jpegtopnm";
                $pipe2 = "{$GLOBALS[$moduleDirName.'_netpbmpath']}pnmtojpeg";
                break;
            case 3:
                // PNG
                $pipe0 = "{$GLOBALS[$moduleDirName.'_netpbmpath']}pngtopnm";
                $pipe2 = "{$GLOBALS[$moduleDirName.'_netpbmpath']}pnmtopng";
                break;
            default:
                @rename($src_path, $dst_path);

                return 2;
        }

        if ($width > $GLOBALS[$moduleDirName . '_width'] || $height > $GLOBALS[$moduleDirName . '_height']) {
            if ($width / $GLOBALS[$moduleDirName . '_width'] > $height / $GLOBALS[$moduleDirName . '_height']) {
                $new_w = $GLOBALS[$moduleDirName . '_width'];
                $scale = $width / $new_w;
                $new_h = (int)round($height / $scale);
            } else {
                $new_h = $GLOBALS[$moduleDirName . '_height'];
                $scale = $height / $new_h;
                $new_w = (int)round($width / $scale);
            }
            $pipe1 .= "{$GLOBALS[$moduleDirName.'_netpbmpath']}pnmscale -xysize $new_w $new_h |";
        }

        if (isset($_POST['rotate'])) {
            switch ($_POST['rotate']) {
                case 'rot270':
                    $pipe1 .= "{$GLOBALS[$moduleDirName.'_netpbmpath']}pnmflip -r90 |";
                    break;
                case 'rot180':
                    $pipe1 .= "{$GLOBALS[$moduleDirName.'_netpbmpath']}pnmflip -r180 |";
                    break;
                case 'rot90':
                    $pipe1 .= "{$GLOBALS[$moduleDirName.'_netpbmpath']}pnmflip -r270 |";
                    break;
                default:
                case 'rot0':
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

    /**
     * @param        $dir_path
     * @param string $prefix
     *
     * @return int
     */
    public static function clearTempFiles($dir_path, $prefix = 'tmp_')
    {
        // return if directory can't be opened
        if (!($dir = @opendir($dir_path))) {
            return 0;
        }

        $ret        = 0;
        $prefix_len = strlen($prefix);
        while (false !== ($file = readdir($dir))) {
            if (0 === strpos($file, $prefix)) {
                if (@unlink("$dir_path/$file")) {
                    ++$ret;
                }
            }
        }
        closedir($dir);

        return $ret;
    }

    //updates rating data in itemtable for a given item

    /**
     * @param $lid
     */
    public static function updateRating($lid)
    {
        $moduleDirName = basename(dirname(__DIR__));
        /** @var MyalbumVotedataHandler $votedataHandler */
        //        $votedataHandler = xoops_getModuleHandler('votedata', $moduleDirName);
        require_once __DIR__ . '/votedata.php';
        $votedataHandler = VotedataHandler::getInstance();
        $criteria        = new CriteriaCompo(new Criteria('`lid`', $lid));
        $votes           = $votedataHandler->getObjects($criteria, true);
        $votesDB         = $votedataHandler->getCount($criteria);
        $totalrating     = 0;
        /** @var MyalbumVotedata $vote */
        foreach ($votes as $vid => $vote) {
            $totalrating += $vote->getVar('rating');
        }
        $finalrating = 0;
        if ($votesDB > 0) {
            $finalrating = number_format($totalrating / $votesDB, 4);
        }
        /** @var MyalbumPhotosHandler $photosHandler */
        //        $photosHandler = xoops_getModuleHandler('photos', $moduleDirName);
        require_once __DIR__ . '/photos.php';
        $photosHandler = PhotosHandler::getInstance();
        $photo         = $photosHandler->get($lid);
        $photo->setVar('rating', $finalrating);
        $photosHandler->insert($photo, true) || exit('Error: DB update rating.');
    }

    // Returns the number of photos included in a Category

    /**
     * @param                      $cid
     * @param CriteriaElement|null $criteria
     * @return mixed
     */
    public static function getCategoryCount($cid, CriteriaElement $criteria = null)
    {
        if (is_object($criteria)) {
            $criteria = new CriteriaCompo($criteria);
        }
        $criteria->add(new Criteria('`cid`', $cid));
        /** @var PhotosHandler $photoHandler */
        //        $photoHandler = xoops_getModuleHandler('photos', $GLOBALS[$moduleDirName.'_dirname']);
        require_once __DIR__ . '/photos.php';
        $photoHandler = PhotosHandler::getInstance();

        return $photoHandler->getCount($criteria);
    }

    // Returns the number of whole photos included in a Category

    /**
     * @param                      $cids
     * @param null|CriteriaElement $criteria
     *
     * @return mixed
     */
    public static function getTotalCount($cids, CriteriaElement $criteria = null)
    {
        if (is_object($criteria)) {
            $criteria = new CriteriaCompo($criteria);
        }
        $criteria->add(new Criteria('`cid`', '(' . implode(',', $cids) . ',0)', 'IN'));
        /** @var MyalbumPhotosHandler $photoHandler */
        //        $photoHandler = xoops_getModuleHandler('photos', $GLOBALS[$moduleDirName.'_dirname']);
        require_once __DIR__ . '/photos.php';
        $photosHandler = PhotosHandler::getInstance();

        return $photosHandler->getCount($criteria);
    }

    // Update a photo

    /**
     * @param        $lid
     * @param        $cid
     * @param        $title
     * @param        $desc
     * @param null   $valid
     * @param string $ext
     * @param string $x
     * @param string $y
     */
    public static function updatePhoto($lid, $cid, $title, $desc, $valid = null, $ext = '', $x = '', $y = '')
    {
        /** @var MyalbumCatHandler $catHandler */
        //        $catHandler = xoops_getModuleHandler('cat', $GLOBALS[$moduleDirName.'_dirname']);
        require_once __DIR__ . '/cat.php';
        $catHandler = CatHandler::getInstance();
        /** @var MyalbumPhotosHandler $photosHandler */
        //        $photosHandler = xoops_getModuleHandler('photos', $GLOBALS[$moduleDirName.'_dirname']);
        require_once __DIR__ . '/photos.php';
        $photosHandler = PhotosHandler::getInstance();

        /** @var MyalbumTextHandler $textHandler */
        //        $textHandler   = xoops_getModuleHandler('text', $GLOBALS[$moduleDirName.'_dirname']);
        require_once __DIR__ . '/text.php';
        $textHandler = TextHandler::getInstance();
        /** @var MyalbumPhotos $photo */
        $photo = $photosHandler->get($lid);
        $text  = $textHandler->get($lid);
        $cat   = $catHandler->get($cid);

        if (null !== $valid) {
            $photo->setVar('status', $valid);
            // Trigger Notification
            if (1 == $valid) {
                /** @var XoopsNotificationHandler $notificationHandler */
                $notificationHandler = xoops_getHandler('notification');

                // Global Notification
                $notificationHandler->triggerEvent('global', 0, 'new_photo', [
                    'PHOTO_TITLE' => $title,
                    'PHOTO_URI'   => $photo->getURL()
                ]);

                // Category Notification

                $cat_title = $cat->getVar('title');
                $notificationHandler->triggerEvent('category', $cid, 'new_photo', [
                    'PHOTO_TITLE'    => $title,
                    'CATEGORY_TITLE' => $cat_title,
                    'PHOTO_URI'      => $photo->getURL()
                ]);
            }
        }

        $photo->setVar('cid', $cid);
        $photo->setVar('title', $title);

        if ('' != $ext) {
            $photo->setVar('ext', $ext);
        }
        if ('' != $x) {
            $photo->setVar('res_x', $x);
        }
        if ('' != $y) {
            $photo->setVar('res_y', $y);
        }

        $cid = empty($_POST['cid']) ? 0 : (int)$_POST['cid'];

        if ($photosHandler->insert($photo, true)) {
            $text->setVar('description', $desc);
            @$textHandler->insert($text, true);
        }

        // not admin can only touch photos status>0
        redirect_header($photo->getEditURL(), 0, _ALBM_DBUPDATED);
    }

    // Delete photos hit by the $whr clause

    /**
     * @param null $criteria
     */
    public static function deletePhotos($criteria = null)
    {
        /** @var MyalbumPhotosHandler $photosHandler */
        //        $photosHandler = xoops_getModuleHandler('photos', $GLOBALS[$moduleDirName.'_dirname']);
        require_once __DIR__ . '/photos.php';
        $photosHandler = PhotosHandler::getInstance();

        $photos = $photosHandler->getObjects($criteria);
        /** @var MyalbumPhotos $photo */
        foreach ($photos as $lid => $photo) {
            $photosHandler->delete($photo);
        }
    }

    // Substitution of opentable()
    public static function openTable()
    {
        echo "<div style='border:2px solid #2F5376; padding:8px; width:95%;' class='bg4'>\n";
    }

    // Substitution of closetable()
    public static function closeTable()
    {
        echo "</div>\n";
    }

    // returns extracted string for options from table with xoops tree

    /**
     * @param string $order
     * @param int    $preset
     * @param string $prefix
     * @param null   $none
     * @param null   $table_name_cat
     * @param null   $table_name_photos
     *
     * @return string
     */
    public static function getCategoryOptions(
        $order = 'title',
        $preset = 0,
        $prefix = '--',
        $none = null,
        $table_name_cat = null,
        $table_name_photos = null
    ) {
        if (empty($table_name_cat)) {
            $table_name_cat = $GLOBALS['xoopsDB']->prefix($GLOBALS['table_cat']);
        }
        if (empty($table_name_photos)) {
            $table_name_photos = $GLOBALS['xoopsDB']->prefix($GLOBALS['table_photos']);
        }

        $cats[0] = [
            'cid'      => 0,
            'pid'      => -1,
            'next_key' => -1,
            'depth'    => 0,
            'title'    => '',
            'num'      => 0
        ];

        $rs = $GLOBALS['xoopsDB']->query("SELECT c.title,c.cid,c.pid,COUNT(p.lid) AS num FROM $table_name_cat c LEFT JOIN $table_name_photos p ON c.cid=p.cid GROUP BY c.cid ORDER BY pid ASC,$order DESC");

        $key = 1;
        while (false !== (list($title, $cid, $pid, $num) = $GLOBALS['xoopsDB']->fetchRow($rs))) {
            $cats[$key] = [
                'cid'      => (int)$cid,
                'pid'      => (int)$pid,
                'next_key' => $key + 1,
                'depth'    => 0,
                'title'    => $GLOBALS['myts']->htmlSpecialChars($title),
                'num'      => (int)$num
            ];
            ++$key;
        }
        $sizeofcats = $key;

        $loop_check_for_key = 1024;
        for ($key = 1; $key < $sizeofcats; ++$key) {
            $cat        =& $cats[$key];
            $target     =& $cats[0];
            $loop_check = 4096;
            if (--$loop_check_for_key < 0) {
                $loop_check = -1;
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
                    $cat_backup =& $cat;
                    array_splice($cats, $key, 1);
                    array_push($cats, $cat_backup);
                    --$key;
                    break;
                }
                $target =& $cats[$target['next_key']];
            }
        }

        $ret = '';
        if (null !== $none) {
            $ret = "<option value=''>$none</option>\n";
        }
        $cat =& $cats[0];
        for ($weight = 1; $weight < $sizeofcats; ++$weight) {
            $cat      =& $cats[$cat['next_key']];
            $pref     = str_repeat($prefix, $cat['depth'] - 1);
            $selected = $preset == $cat['cid'] ? 'selected' : '';
            $ret      .= "<option value='{$cat['cid']}' $selected>$pref {$cat['title']} ({$cat['num']})</option>\n";
        }

        return $ret;
    }

    /**
     * @param $html
     *
     * @return string
     */
    public static function extractSummary($html)
    {
        $html = $GLOBALS['myts']->displayTarea($html, 1, 1, 1, 1, 1, 1, 1);
        $ret  = '';
        $i    = 0;
        if ('' != $html) {
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
            }
            //        else {
            //            continue;
            //        }
        }

        return trim($ret);
    }
}
