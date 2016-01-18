<?php
// $Id: xoops_version.php,v 1.4 2003/02/12 11:37:53 okazu Exp $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //

//defined('XOOPS_ROOT_PATH') || die('XOOPS root path not defined');

$moduleDirName = basename(__DIR__);

if (preg_match('/^myalbum(\d*)$/', $moduleDirName, $regs)) {
    $myalbum_number = $regs[1];
} else {
    echo "invalid dirname of myalbum: " . htmlspecialchars($moduleDirName);
}
$_SESSION['myalbum_mydirname'] = $moduleDirName;

// ------------------- Informations ------------------- //
$modversion = array(
    'name'                => _ALBM_MYALBUM_NAME . $myalbum_number,
    'description'         => _ALBM_MYALBUM_DESC,
    'official'            => 0, //1 indicates supported by XOOPS Dev Team, 0 means 3rd party supported
    'author'              => 'GIJoe, http://xoops.peak.ne.jp<br />Updates: Wishcraft=Simon Roberts, montuy337513 alias black_beard',
    'credits'             => "Original: Daniel Branco<br />(http://bluetopia.homeip.net)<br />Kazumi Ono<br />(http://www.mywebaddons.com/)<br />Wishcraft=Simon Roberts<br />Chronolabs Co-op.(http://www.chronolabs.coop/)<br />The XOOPS Project, Chronolabs Co-op - http://www.chronolabs.coop/",
    'author_mail'         => '',
    'author_website_url'  => 'http://xoops.org',
    'author_website_name' => 'XOOPS',
    'license'             => 'GPL 2.0 or later',
    'license_url'         => 'www.gnu.org/licenses/gpl-2.0.html/',
    'help'                => 'page=help',
    //
    'release_info'        => 'Changelog',
    'release_file'        => XOOPS_URL . "/modules/$moduleDirName/docs/changelog file",
    //
    'manual'              => 'link to manual file',
    'manual_file'         => XOOPS_URL . "/modules/$moduleDirName/docs/install.txt",
    'min_php'             => '5.5',
    'min_xoops'           => '2.5.7.2',
    'min_admin'           => '1.1',
    'min_db'              => array('mysql' => '5.0.7', 'mysqli' => '5.0.7'),
    // images
    'image'               => "assets/images/{$moduleDirName}_slogo.gif", //'assets/images/module_logo.png',
//    'iconsmall'           => 'assets/images/iconsmall.png',
//    'iconbig'             => 'assets/images/iconbig.png',
    'dirname'             => $moduleDirName,
    //Frameworks
    'dirmoduleadmin'      => 'Frameworks/moduleclasses/moduleadmin',
    'sysicons16'          => 'Frameworks/moduleclasses/icons/16',
    'sysicons32'          => 'Frameworks/moduleclasses/icons/32',
    // Local path icons
    'modicons16'          => 'assets/images/icons/16',
    'modicons32'          => 'assets/images/icons/32',
    //About
    'version'             => 3.08,
    'module_status'       => 'Beta 1',
    'release_date'        => '2015/09/23', //yyyy/mm/dd
    //    'release'             => '2015-04-04',
    'demo_site_url'       => 'http://www.xoops.org',
    'demo_site_name'      => 'XOOPS Demo Site',
    'support_url'         => 'http://xoops.org/modules/newbb',
    'submit_bug'          => 'http://xoops.org/modules/newbb/viewforum.php?forum=28',
    'submit_feature'      => 'http://xoops.org/modules/newbb/viewforum.php?forum=30',
    'support_name'        => 'XOOPS Support Forum',
    'module_website_url'  => 'www.xoops.org',
    'module_website_name' => 'XOOPS Project',
    // Admin system menu
    'system_menu'         => 1,
    // Admin menu
    'hasAdmin'            => 1,
    'adminindex'          => 'admin/index.php',
    'adminmenu'           => 'admin/menu.php',
    // Main menu
    'hasMain'             => 1,
    //Search & Comments
    //    'hasSearch'           => 1,
    //    'search'              => array(
    //        'file'   => 'include/search.inc.php',
    //        'func'   => 'XXXX_search'),
    //    'hasComments'         => 1,
    //    'comments'              => array(
    //        'pageName'   => 'index.php',
    //        'itemName'   => 'id'),

    // Install/Update
    'onInstall'           => 'include/oninstall.php',
    'onUpdate'            => 'include/onupdate.php'
    //  'onUninstall'         => 'include/onuninstall.php'

);


//$modversion['author_realname']        = 'GIJoe';
//$modversion['author_website_url']     = 'http://xoops.peak.ne.jp';
//$modversion['author_website_name']    = 'PEAK';


// Sql file (must contain sql generated by phpMyAdmin or phpPgAdmin)
// All tables should not have any prefix!
$modversion['sqlfile']['mysql'] = "sql/{$moduleDirName}.sql";
//$modversion['sqlfile']['postgresql'] = "sql/pgsql.sql";

// Tables created by sql file (without prefix!)
$modversion['tables'][0] = "{$moduleDirName}_cat";
$modversion['tables'][1] = "{$moduleDirName}_photos";
$modversion['tables'][2] = "{$moduleDirName}_text";
$modversion['tables'][3] = "{$moduleDirName}_votedata";


// ------------------- Blocks ------------------- //
$modversion['blocks'][] = array(
    'file'        => $moduleDirName . '_rphoto.php',
    'name'        => _ALBM_BNAME_RANDOM . $myalbum_number,
    'description' => 'Shows a random photo',
    'show_func'   => 'b_myalbum_rphoto_show',
    'edit_func'   => 'b_myalbum_rphoto_edit',
    'options'     => $moduleDirName . '|140|1||1|60|1',
    'template'    => $moduleDirName . '_block_rphoto.tpl',);

$modversion['blocks'][] = array(
    'file'        => $moduleDirName . '_topnews.php',
    'name'        => _ALBM_BNAME_RECENT . $myalbum_number,
    'description' => 'Shows recently added photos',
    'show_func'   => 'b_myalbum_topnews_show',
    'edit_func'   => 'b_myalbum_topnews_edit',
    'options'     => $moduleDirName . '|10|20||1||1',
    'template'    => $moduleDirName . '_block_topnews.tpl',);

$modversion['blocks'][] = array(
    'file'        => $moduleDirName . '_tophits.php',
    'name'        => _ALBM_BNAME_HITS . $myalbum_number,
    'description' => 'Shows most viewed photos',
    'show_func'   => 'b_myalbum_tophits_show',
    'edit_func'   => 'b_myalbum_tophits_edit',
    'options'     => $moduleDirName . '|10|20||1||1',
    'template'    => $moduleDirName . '_block_tophits.tpl',);

$modversion['blocks'][] = array(
    'file'        => $moduleDirName . '_topnews.php',
    'name'        => _ALBM_BNAME_RECENT_P . $myalbum_number,
    'description' => 'Shows recently added photos',
    'show_func'   => 'b_myalbum_topnews_show',
    'edit_func'   => 'b_myalbum_topnews_edit',
    'options'     => $moduleDirName . '|5|20||1||1',
    'template'    => $moduleDirName . '_block_topnews_p.tpl',);

$modversion['blocks'][] = array(
    'file'        => $moduleDirName . '_tophits.php',
    'name'        => _ALBM_BNAME_HITS_P . $myalbum_number,
    'description' => 'Shows most viewed photos',
    'show_func'   => 'b_myalbum_tophits_show',
    'edit_func'   => 'b_myalbum_tophits_edit',
    'options'     => $moduleDirName . '|5|20||1||1',
    'template'    => $moduleDirName . '_block_tophits_p.tpl',);

$modversion['blocks'][] = array(
    'file'        => $moduleDirName . '_block_tag.php',
    'name'        => $modversion['name'] . ' Tag Cloud',
    'description' => 'Show tag cloud',
    'show_func'   => $moduleDirName . '_tag_block_cloud_show',
    'edit_func'   => $moduleDirName . '_tag_block_cloud_edit',
    'options'     => '100|0|150|80',
    'template'    => $moduleDirName . '_tag_block_cloud.tpl',);

$modversion['blocks'][] = array(
    'file'        => $moduleDirName . '_block_tag.php',
    'name'        => $modversion['name'] . ' Top Tags',
    'description' => 'Show top tags',
    'show_func'   => $moduleDirName . '_tag_block_top_show',
    'edit_func'   => $moduleDirName . '_tag_block_top_edit',
    'options'     => '50|30|c',
    'template'    => $moduleDirName . '_tag_block_top.tpl',);

// Menu
$modversion['hasMain'] = 1;
$subcount              = 1;
// Ajout black_beard alias MONTUY337513
$GLOBALS['global_perms'] = 0;
// Fin de l'ajout
include(__DIR__ . '/include/get_perms.php');
if ($GLOBALS['global_perms'] & 1) { // GPERM_INSERTABLE
    $modversion['sub'][$subcount]['name']  = _ALBM_TEXT_SMNAME1;
    $modversion['sub'][$subcount++]['url'] = "submit.php";
    $modversion['sub'][$subcount]['name']  = _ALBM_TEXT_SMNAME4;
    $modversion['sub'][$subcount++]['url'] = "viewcat.php?uid=" . (is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getVar('uid') : -1);
}
$modversion['sub'][$subcount]['name']  = _ALBM_TEXT_SMNAME2;
$modversion['sub'][$subcount++]['url'] = "topten.php?hit=1";
if ($GLOBALS['global_perms'] & 256) { // GPERM_RATEVIEW
    $modversion['sub'][$subcount]['name']  = _ALBM_TEXT_SMNAME3;
    $modversion['sub'][$subcount++]['url'] = "topten.php?rate=1";
}
if (isset($myalbum_catonsubmenu) && $myalbum_catonsubmenu) {
    $criteria    = new Criteria('`pid`', 0);
    $cat_handler =& xoops_getmodulehandler('cat', $GLOBALS['mydirname']);
    if ($cat_handler->getCount($criteria) !== false) {
        foreach ($cat_handler->getObjects($criteria, true) as $cid => $cat) {
            $modversion['sub'][$subcount]['name']  = " - " . $cat->getVar('title');
            $modversion['sub'][$subcount++]['url'] = "viewcat.php?cid=$cid";
        }
    }
}

// Config
xoops_load('XoopsEditorHandler');
$editor_handler = XoopsEditorHandler::getInstance();
foreach ($editor_handler->getList(false) as $id => $val) {
    $options[$val] = $id;
}

$modversion['config'][] = array(
    'name'        => 'editor',
    'title'       => '_ALBM_CFG_EDITOR',
    'description' => '_ALBM_CFG_DESCEDITOR',
    'formtype'    => 'select',
    'valuetype'   => 'text',
    'default'     => "tinymce",
    'options'     => $options);

$modversion['config'][] = array(
    'name'        => 'myalbum_photospath',
    'title'       => '_ALBM_CFG_PHOTOSPATH',
    'description' => '_ALBM_CFG_DESCPHOTOSPATH',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => "/uploads/{$moduleDirName}/photos{$myalbum_number}",
    'options'     => array());
$modversion['config'][] = array(
    'name'        => 'myalbum_thumbspath',
    'title'       => '_ALBM_CFG_THUMBSPATH',
    'description' => '_ALBM_CFG_DESCTHUMBSPATH',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => "/uploads/{$moduleDirName}/thumbs{$myalbum_number}",
    'options'     => array());
$modversion['config'][] = array(
    'name'        => 'myalbum_imagingpipe',
    'title'       => '_ALBM_CFG_IMAGINGPIPE',
    'description' => '_ALBM_CFG_DESCIMAGINGPIPE',
    'formtype'    => 'select',
    'valuetype'   => 'int',
    'default'     => '0',
    'options'     => array('GD' => 0, 'ImageMagick' => 1, 'NetPBM' => 2));
$modversion['config'][] = array(
    'name'        => 'myalbum_forcegd2',
    'title'       => '_ALBM_CFG_FORCEGD2',
    'description' => '_ALBM_CFG_DESCFORCEGD2',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => '0',
    'options'     => array());
$modversion['config'][] = array(
    'name'        => 'myalbum_imagickpath',
    'title'       => '_ALBM_CFG_IMAGICKPATH',
    'description' => '_ALBM_CFG_DESCIMAGICKPATH',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => '',
    'options'     => array());
$modversion['config'][] = array(
    'name'        => 'myalbum_netpbmpath',
    'title'       => '_ALBM_CFG_NETPBMPATH',
    'description' => '_ALBM_CFG_DESCNETPBMPATH',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => '',
    'options'     => array());
$modversion['config'][] = array(
    'name'        => 'myalbum_width',
    'title'       => '_ALBM_CFG_WIDTH',
    'description' => '_ALBM_CFG_DESCWIDTH',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => '1024',
    'options'     => array());
$modversion['config'][] = array(
    'name'        => 'myalbum_height',
    'title'       => '_ALBM_CFG_HEIGHT',
    'description' => '_ALBM_CFG_DESCHEIGHT',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => '1024',
    'options'     => array());
$modversion['config'][] = array(
    'name'        => 'myalbum_fsize',
    'title'       => '_ALBM_CFG_FSIZE',
    'description' => '_ALBM_CFG_DESCFSIZE',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => '100000',
    'options'     => array());
$modversion['config'][] = array(
    'name'        => 'myalbum_middlepixel',
    'title'       => '_ALBM_CFG_MIDDLEPIXEL',
    'description' => '_ALBM_CFG_DESCMIDDLEPIXEL',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => '480x480',
    'options'     => array());
$modversion['config'][] = array(
    'name'        => 'myalbum_allownoimage',
    'title'       => '_ALBM_CFG_ALLOWNOIMAGE',
    'description' => '',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => '1',
    'options'     => array());
$modversion['config'][] = array(
    'name'        => 'myalbum_makethumb',
    'title'       => '_ALBM_CFG_MAKETHUMB',
    'description' => '_ALBM_CFG_DESCMAKETHUMB',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => '0',
    'options'     => array());
$modversion['config'][] = array(
    'name'        => 'myalbum_thumbsize',
    'title'       => '_ALBM_CFG_THUMBSIZE',
    'description' => '',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => '140',
    'options'     => array());
$modversion['config'][] = array(
    'name'        => 'myalbum_thumbrule',
    'title'       => '_ALBM_CFG_THUMBRULE',
    'description' => '',
    'formtype'    => 'select',
    'valuetype'   => 'text',
    'default'     => 'w',
    'options'     => array(
        '_ALBUM_OPT_CALCFROMWIDTH'   => 'w',
        '_ALBUM_OPT_CALCFROMHEIGHT'  => 'h',
        '_ALBUM_OPT_CALCWHINSIDEBOX' => 'b'));
$modversion['config'][] = array(
    'name'        => 'myalbum_popular',
    'title'       => '_ALBM_CFG_POPULAR',
    'description' => '',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => '100',
    'options'     => array());
$modversion['config'][] = array(
    'name'        => 'myalbum_newdays',
    'title'       => '_ALBM_CFG_NEWDAYS',
    'description' => '',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => '7',
    'options'     => array());
$modversion['config'][] = array(
    'name'        => 'myalbum_newphotos',
    'title'       => '_ALBM_CFG_NEWPHOTOS',
    'description' => '',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => '10',
    'options'     => array());
$modversion['config'][] = array(
    'name'        => 'myalbum_defaultorder',
    'title'       => '_ALBM_CFG_DEFAULTORDER',
    'description' => '',
    'formtype'    => 'select',
    'valuetype'   => 'text',
    'default'     => 'dateD',
    'options'     => array(
        "cat_id ASC"  => 'cidA',
        "photo_id ASC"  => 'lidA',
        "title ASC"     => 'titleA',
        "date ASC"      => 'dateA',
        "hits ASC"      => 'hitsA',
        "rating ASC"    => 'ratingA',
        "cat_id DESC"  => 'cidD',
        "photo_id DESC" => 'lidD',
        "title DESC"    => 'titleD',
        "date DESC"     => 'dateD',
        "hits DESC"     => 'hitsD',
        "rating DESC"   => 'ratingD'));
$modversion['config'][] = array(
    'name'        => 'myalbum_perpage',
    'title'       => '_ALBM_CFG_PERPAGE',
    'description' => '_ALBM_CFG_DESCPERPAGE',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => '10|20|50|100',
    'options'     => array());
$modversion['config'][] = array(
    'name'        => 'myalbum_addposts',
    'title'       => '_ALBM_CFG_ADDPOSTS',
    'description' => '_ALBM_CFG_DESCADDPOSTS',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => '1',
    'options'     => array());
$modversion['config'][] = array(
    'name'        => 'myalbum_catonsubmenu',
    'title'       => '_ALBM_CFG_CATONSUBMENU',
    'description' => '',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => '0',
    'options'     => array());
$modversion['config'][] = array(
    'name'        => 'myalbum_nameoruname',
    'title'       => '_ALBM_CFG_NAMEORUNAME',
    'description' => '_ALBM_CFG_DESCNAMEORUNAME',
    'formtype'    => 'select',
    'valuetype'   => 'text',
    'default'     => 'uname',
    'options'     => array('_ALBM_OPT_USENAME' => 'name', '_ALBM_OPT_USEUNAME' => 'uname'));
$modversion['config'][] = array(
    'name'        => 'myalbum_viewcattype',
    'title'       => '_ALBM_CFG_VIEWCATTYPE',
    'description' => '',
    'formtype'    => 'select',
    'valuetype'   => 'text',
    'default'     => 'list',
    'options'     => array('_ALBM_OPT_VIEWLIST' => 'list', '_ALBM_OPT_VIEWTABLE' => 'table'));
$modversion['config'][] = array(
    'name'        => 'myalbum_colsoftableview',
    'title'       => '_ALBM_CFG_COLSOFTABLEVIEW',
    'description' => '',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => '4',
    'options'     => array());
$modversion['config'][] = array(
    'name'        => 'myalbum_allowedexts',
    'title'       => '_ALBM_CFG_ALLOWEDEXTS',
    'description' => '_ALBM_CFG_DESCALLOWEDEXTS',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => 'jpg|jpeg|gif|png',
    'options'     => array());
$modversion['config'][] = array(
    'name'        => 'myalbum_allowedmime',
    'title'       => '_ALBM_CFG_ALLOWEDMIME',
    'description' => '_ALBM_CFG_DESCALLOWEDMIME',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => 'image/gif|image/pjpeg|image/jpeg|image/x-png|image/png',
    'options'     => array());
$modversion['config'][] = array(
    'name'        => 'myalbum_usesiteimg',
    'title'       => '_ALBM_CFG_USESITEIMG',
    'description' => '_ALBM_CFG_DESCUSESITEIMG',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => '0',
    'options'     => array());
$modversion['config'][] = array(
    'name'        => 'tag',
    'title'       => '_ALBM_CFG_TAG',
    'description' => '_ALBM_CFG_DESCTAG',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => '0',
    'options'     => array());
$modversion['config'][] = array(
    'name'        => 'htaccess',
    'title'       => '_ALBM_CFG_HTACCESS',
    'description' => '_ALBM_CFG_DESCHTACCESS',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => '0',
    'options'     => array());
$modversion['config'][] = array(
    'name'        => 'baseurl',
    'title'       => '_ALBM_CFG_BASEOFURL',
    'description' => '_ALBM_CFG_DESCBASEOFURL',
    'formtype'    => 'text',
    'valuetype'   => 'text',
    'default'     => 'gallery',
    'options'     => array());
$modversion['config'][] = array(
    'name'        => 'endofurl',
    'title'       => '_ALBM_CFG_ENDOFURL',
    'description' => '_ALBM_CFG_DESCENDOFURL',
    'formtype'    => 'text',
    'valuetype'   => 'text',
    'default'     => '.tpl',
    'options'     => array());
$modversion['config'][] = array(
    'name'        => 'endofrss',
    'title'       => '_ALBM_CFG_ENDOFRSS',
    'description' => '_ALBM_CFG_DESCENDOFRSS',
    'formtype'    => 'text',
    'valuetype'   => 'text',
    'default'     => '.rss',
    'options'     => array());

// Search
$modversion['hasSearch']      = 1;
$modversion['search']['file'] = "include/search.inc.php";
$modversion['search']['func'] = "{$moduleDirName}_search";

// Comments
$modversion['hasComments']          = 1;
$modversion['comments']['itemName'] = 'lid';
$modversion['comments']['pageName'] = 'photo.php';
// Comment callback functions
$modversion['comments']['callbackFile']        = 'include/comment_functions.php';
$modversion['comments']['callback']['approve'] = 'myalbum_comments_approve';
$modversion['comments']['callback']['update']  = 'myalbum_comments_update';

// ------------------- Templates ------------------- //

$modversion['templates'] = array(
    array('file' => $moduleDirName . '_photo.tpl', 'description' => ''),
    array('file' => $moduleDirName . '_viewcat_list.tpl', 'description' => ''),
    array('file' => $moduleDirName . '_viewcat_table.tpl', 'description' => ''),
    array('file' => $moduleDirName . '_index.tpl', 'description' => ''),
    array('file' => $moduleDirName . '_ratephoto.tpl', 'description' => ''),
    array('file' => $moduleDirName . '_topten.tpl', 'description' => ''),
    array('file' => $moduleDirName . '_photo_in_list.tpl', 'description' => ''),
    array('file' => $moduleDirName . '_header.tpl', 'description' => ''),
    array('file' => $moduleDirName . '_footer.tpl', 'description' => ''),
    array('file' => $moduleDirName . '_categories.tpl', 'description' => ''),
    array('file' => $moduleDirName . '_imagemanager.tpl', 'description' => ''),
    array('file' => $moduleDirName . '_cpanel_admission.tpl', 'description' => ''),
    array('file' => $moduleDirName . '_cpanel_batch.tpl', 'description' => ''),
    array('file' => $moduleDirName . '_cpanel_export.tpl', 'description' => ''),
    array('file' => $moduleDirName . '_cpanel_import.tpl', 'description' => ''),
    array('file' => $moduleDirName . '_cpanel_permissions.tpl', 'description' => ''),
    array('file' => $moduleDirName . '_rss.tpl', 'description' => ''));


//Install
$modversion['onInstall'] = "include/oninstall.inc.php";
$modversion['onUpdate']  = "include/onupdate.inc.php";

// Notification
$modversion['hasNotification']             = 1;
$modversion['notification']['lookup_file'] = 'include/notification.inc.php';
$modversion['notification']['lookup_func'] = $moduleDirName . '_notify_iteminfo';

$modversion['notification']['category'][] = array(
    'name'           => 'global',
    'title'          => _MI_MYALBUM_GLOBAL_NOTIFY,
    'description'    => _MI_MYALBUM_GLOBAL_NOTIFYDSC,
    'subscribe_from' => array('index.php', 'viewcat.php', 'photo.php'));

$modversion['notification']['category'][] = array(
    'name'           => 'category',
    'title'          => _MI_MYALBUM_CATEGORY_NOTIFY,
    'description'    => _MI_MYALBUM_CATEGORY_NOTIFYDSC,
    'subscribe_from' => array('viewcat.php', 'photo.php'),
    'item_name'      => 'cid',
    'allow_bookmark' => 1);

$modversion['notification']['category'][] = array(
    'name'           => 'photo',
    'title'          => _MI_MYALBUM_PHOTO_NOTIFY,
    'caption'        => _MI_MYALBUM_GLOBAL_NEWPHOTO_NOTIFYCAP,
    'description'    => _MI_MYALBUM_PHOTO_NOTIFYDSC,
    'subscribe_from' => array('photo.php'),
    'item_name'      => 'lid',
    'allow_bookmark' => 1);

$modversion['notification']['event'][] = array(
    'name'          => 'new_photo',
    'category'      => 'global',
    'title'         => _MI_MYALBUM_GLOBAL_NEWPHOTO_NOTIFY,
    'caption'       => _MI_MYALBUM_GLOBAL_NEWPHOTO_NOTIFYCAP,
    'description'   => _MI_MYALBUM_GLOBAL_NEWPHOTO_NOTIFYDSC,
    'mail_template' => 'global_newphoto_notify',
    'mail_subject'  => _MI_MYALBUM_GLOBAL_NEWPHOTO_NOTIFYSBJ);

$modversion['notification']['event'][] = array(
    'name'          => 'new_photo',
    'category'      => 'category',
    'title'         => _MI_MYALBUM_CATEGORY_NEWPHOTO_NOTIFY,
    'caption'       => _MI_MYALBUM_CATEGORY_NEWPHOTO_NOTIFYCAP,
    'description'   => _MI_MYALBUM_CATEGORY_NEWPHOTO_NOTIFYDSC,
    'mail_template' => 'category_newphoto_notify',
    'mail_subject'  => _MI_MYALBUM_CATEGORY_NEWPHOTO_NOTIFYSBJ);
