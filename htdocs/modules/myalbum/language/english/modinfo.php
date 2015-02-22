<?php
// Module Info
if ( defined( "FOR_XOOPS_LANG_CHECKER" ) || ! defined( "MYALBUM_MI_LOADED" ) ) {
define( "MYALBUM_MI_LOADED", 1 );
// The name of this module
define("_ALBM_MYALBUM_NAME","MyAlbum");
// A brief description of this module
define("_ALBM_MYALBUM_DESC","Creates a photos section where users can search/submit/rate various photos.");
// Names of blocks for this module (Not all module has blocks)
define("_ALBM_BNAME_RECENT","Recent Photos");
define("_ALBM_BNAME_HITS","Top Photos");
define("_ALBM_BNAME_RANDOM","Pic Up Photo");
define("_ALBM_BNAME_RECENT_P","Recent Photos with thumbs");
define("_ALBM_BNAME_HITS_P","Top Photos with thumbs");
// Config Items
define("_ALBM_CFG_PHOTOSPATH","Path to photos");
define("_ALBM_CFG_DESCPHOTOSPATH","Path from the directory installed XOOPS.<br />(The first character must be '/'. The last character should not be '/'.)<br />This directory's permission is 777 or 707 in unix.");
define("_ALBM_CFG_THUMBSPATH" , "Path to thumbnails");
define("_ALBM_CFG_DESCTHUMBSPATH","Same as 'Path to photos'.");
//define("_ALBM_CFG_USEIMAGICK","Use ImageMagick for treating images");
//define("_ALBM_CFG_DESCIMAGICK","Not use ImageMagick cause Not work resize or rotate the main photo, and make thumbnails by GD.<br />You'd better use ImageMagick if you can.");
define("_ALBM_CFG_IMAGINGPIPE","Package treating images");
define("_ALBM_CFG_DESCIMAGINGPIPE","Almost PHP environment can use GD. But GD is functionally inferior than another 2 packages.<br />You'd better use ImageMagick or NetPBM if you can.");
define("_ALBM_CFG_FORCEGD2","Force GD2 conversion");
define("_ALBM_CFG_DESCFORCEGD2","Even if the GD is bundled version of PHP, it force GD2(truecolor) conversion.<br />Some configured PHP fails to create thumbnails in GD2<br />This configuration is significant only under using GD");
define("_ALBM_CFG_IMAGICKPATH","Path of ImageMagick");
define("_ALBM_CFG_DESCIMAGICKPATH","Though the full path to 'convert' should be written, leave blank in almost environment.<br />This configuration is significant only under using ImageMagick");
define("_ALBM_CFG_NETPBMPATH","Path of NetPBM");
define("_ALBM_CFG_DESCNETPBMPATH","Though the full path to 'pnmscale' should be written, leave blank in almost environment.<br />This configuration is significant only under using NetPBM");
define("_ALBM_CFG_POPULAR","Hits to be Popular");
define("_ALBM_CFG_NEWDAYS","Days between displaying icon of 'new'&'update'");
define("_ALBM_CFG_NEWPHOTOS","Number of Photos as New on Top Page");
define("_ALBM_CFG_DEFAULTORDER","Default order in category's view");
define("_ALBM_CFG_PERPAGE","Displayed Photos per Page");
define("_ALBM_CFG_DESCPERPAGE","Input selectable numbers separated with '|'<br />eg) 10|20|50|100");
define("_ALBM_CFG_ALLOWNOIMAGE","Allows a sumbit without images");
define("_ALBM_CFG_MAKETHUMB","Make Thumb Image");
define("_ALBM_CFG_DESCMAKETHUMB","When you change 'No' to 'Yes', You'd better 'Redo thumbnails'.");
//define("_ALBM_CFG_THUMBWIDTH","Thumb Image Width");
//define("_ALBM_CFG_DESCTHUMBWIDTH","The height of thumbs will be decided from the width automatically.");
define("_ALBM_CFG_THUMBSIZE","Size of thumbnails (pixel)");
define("_ALBM_CFG_THUMBRULE","Calc rule for building thumbnails");
define("_ALBM_CFG_WIDTH","Max photo width");
define("_ALBM_CFG_DESCWIDTH","This means the photo's width to be resized.<br />If you use GD without truecolor, this means the limitation of width.");
define("_ALBM_CFG_HEIGHT","Max photo height");
define("_ALBM_CFG_DESCHEIGHT","Same as 'Max photo width'.");
define("_ALBM_CFG_FSIZE","Max file size");
define("_ALBM_CFG_DESCFSIZE","The limitation of the size of uploading file.(byte)");
define("_ALBM_CFG_MIDDLEPIXEL","Max image size in single view");
define("_ALBM_CFG_DESCMIDDLEPIXEL","Specify (width)x(height)<br />eg) 480x480");
define("_ALBM_CFG_ADDPOSTS","The number added User's posts by posting a photo.");
define("_ALBM_CFG_DESCADDPOSTS","Normally, 0 or 1. Under 0 mean 0");
define("_ALBM_CFG_CATONSUBMENU","Register top categories into submenu");
define("_ALBM_CFG_NAMEORUNAME","Poster name displayed");
define("_ALBM_CFG_DESCNAMEORUNAME","Select which 'name' is displayed");
define("_ALBM_CFG_VIEWCATTYPE","Type of view in category");
define("_ALBM_CFG_COLSOFTABLEVIEW","Number of columns in table view");
define("_ALBM_CFG_ALLOWEDEXTS","File extensions can be uploaded");
define("_ALBM_CFG_DESCALLOWEDEXTS","Input extensions with separator '|'. (eg 'jpg|jpeg|gif|png') .<br />All character must be small. Don't insert periods or spaces<br />Never add php or phtml etc.");
define("_ALBM_CFG_ALLOWEDMIME","MIME Types can be uploaded");
define("_ALBM_CFG_DESCALLOWEDMIME","Input MIME Types with separator '|'. (eg 'image/gif|image/jpeg|image/png')<br />If you want to be checked by MIME Type, be blank here");
define("_ALBM_CFG_USESITEIMG","Use [siteimg] in ImageManager Integration");
define("_ALBM_CFG_DESCUSESITEIMG","The Integrated Image Manager input [siteimg] instead of [img].<br />You have to hack module.textsanitizer.php or each modules to enable tag of [siteimg]");
define("_ALBM_OPT_USENAME","Handle Name");
define("_ALBM_OPT_USEUNAME","Login Name");
define("_ALBUM_OPT_CALCFROMWIDTH","width:specified height:auto");
define("_ALBUM_OPT_CALCFROMHEIGHT","width:auto width:specified");
define("_ALBUM_OPT_CALCWHINSIDEBOX","put in specified size squre");
define("_ALBM_OPT_VIEWLIST","List View");
define("_ALBM_OPT_VIEWTABLE","Table View");
// Sub menu titles
define("_ALBM_TEXT_SMNAME1","Submit");
define("_ALBM_TEXT_SMNAME2","Popular");
define("_ALBM_TEXT_SMNAME3","Top Rated");
define("_ALBM_TEXT_SMNAME4","My Photos");
// Names of admin menu items
define("_ALBM_MYALBUM_ADMENU0","Submitted Photos");
define("_ALBM_MYALBUM_ADMENU1","Photo Management");
define("_ALBM_MYALBUM_ADMENU2","Add/Edit Categories");
define("_ALBM_MYALBUM_ADMENU_GPERM","Global Permissions");
define("_ALBM_MYALBUM_ADMENU3","Check Confs&Envs");
define("_ALBM_MYALBUM_ADMENU4","Batch Register");
define("_ALBM_MYALBUM_ADMENU5","Rebuild Thumbnails");
define("_ALBM_MYALBUM_ADMENU_IMPORT","Import Images");
define("_ALBM_MYALBUM_ADMENU_EXPORT","Export Images");
define("_ALBM_MYALBUM_ADMENU_MYBLOCKSADMIN","Blocks&Groups Admin");
// Text for notifications
define("_MI_MYALBUM_GLOBAL_NOTIFY","Global");
define("_MI_MYALBUM_GLOBAL_NOTIFYDSC","Global notification options with myAlbum-P");
define("_MI_MYALBUM_CATEGORY_NOTIFY","Category");
define("_MI_MYALBUM_CATEGORY_NOTIFYDSC","Notification options that apply to the current photo category");
define("_MI_MYALBUM_PHOTO_NOTIFY","Photo");
define("_MI_MYALBUM_PHOTO_NOTIFYDSC","Notification options that apply to the current photo");
define("_MI_MYALBUM_GLOBAL_NEWPHOTO_NOTIFY","New Photo");
define("_MI_MYALBUM_GLOBAL_NEWPHOTO_NOTIFYCAP","Notify me when any new photo is posted");
define("_MI_MYALBUM_GLOBAL_NEWPHOTO_NOTIFYDSC","Receive notification when any new photo is posted");
define("_MI_MYALBUM_GLOBAL_NEWPHOTO_NOTIFYSBJ","[{X_SITENAME}] {X_MODULE}: auto-notify : New photo");
define("_MI_MYALBUM_CATEGORY_NEWPHOTO_NOTIFY","New Photo");
define("_MI_MYALBUM_CATEGORY_NEWPHOTO_NOTIFYCAP","Notify me when a new photo is posted to the current category");
define("_MI_MYALBUM_CATEGORY_NEWPHOTO_NOTIFYDSC","Receive notification when a new photo is posted to the current category");
define("_MI_MYALBUM_CATEGORY_NEWPHOTO_NOTIFYSBJ","[{X_SITENAME}] {X_MODULE}: auto-notify : New photo");
// Version 3.01
// Admin
define("_ALBM_MYALBUM_ADMENU_ABOUT","About");
define("_ALBM_MYALBUM_ADMENU_DASHBOARD","Dashboard");
// Preferences
define("_ALBM_CFG_EDITOR","Editor to Use");
define("_ALBM_CFG_DESCEDITOR","This is the editor to use in the application/module.");
define("_ALBM_CFG_TAG","Support Tag Module");
define("_ALBM_CFG_DESCTAG","Enable this if you wish to support tag module 2.3 or later.");
define("_ALBM_CFG_HTACCESS","Enable .htaccess SEO");
define("_ALBM_CFG_DESCHTACCESS","Enable this if you want to use .htaccess SEO (see /docs)");
define("_ALBM_CFG_BASEOFURL","Start of URL for .htaccess SEO");
define("_ALBM_CFG_DESCBASEOFURL","If you change this you will have to modify .htaccess SEO (see /docs)");
define("_ALBM_CFG_ENDOFURL","End of URL for .htaccess SEO (HTML output)");
define("_ALBM_CFG_DESCENDOFURL","If you change this you will have to modify .htaccess SEO (see /docs)");
define("_ALBM_CFG_ENDOFRSS","End of URL for .htaccess SEO (RSS output)");
define("_ALBM_CFG_DESCENDOFRSS","If you change this you will have to modify .htaccess SEO (see /docs)");
define("_ALBM_ACTION","Actions");
}
