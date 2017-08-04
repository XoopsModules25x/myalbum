<?php
if (defined('FOR_XOOPS_LANG_CHECKER') || !defined('MYALBUM_AM_LOADED')) {
    define('MYALBUM_AM_LOADED', 1);
    // Index (Categories)
    define('_AM_H3_FMT_CATEGORIES', 'Categories Manager (%s)');
    define('_AM_CAT_TH_WEIGHT', 'Weight');
    define('_AM_CAT_TH_TITLE', 'Name');
    define('_AM_CAT_TH_PHOTOS', 'Images');
    define('_AM_CAT_TH_OPERATION', 'Operation');
    define('_AM_CAT_TH_IMAGE', 'Banner');
    define('_AM_CAT_TH_PARENT', 'Parent');
    define('_AM_CAT_TH_IMGURL', 'URL of Banner');
    define('_AM_CAT_MENU_NEW', 'Creating a category');
    define('_AM_CAT_MENU_EDIT', 'Editing a category');
    define('_AM_CAT_INSERTED', 'A category is added');
    define('_AM_CAT_UPDATED', 'The category is modified');
    define('_AM_CAT_BTN_BATCH', 'Apply');
    define('_AM_CAT_LINK_MAKETOPCAT', 'Create a new category on top');
    define('_AM_CAT_LINK_ADDPHOTOS', 'Add a image into this category');
    define('_AM_CAT_LINK_EDIT', 'Edit this category');
    define('_AM_CAT_LINK_MAKESUBCAT', 'Create a new category under this category');
    define('_AM_CAT_FMT_NEEDADMISSION', '%s images are needed the admission');
    define('_AM_CAT_FMT_CATDELCONFIRM', '%s will be deleted with its sub-categories, images, comments. Are you OK with it?');
    // Admission
    define('_AM_H3_FMT_ADMISSION', 'Admitting images (%s)');
    define('_AM_TH_SUBMITTER', 'Submitter');
    define('_AM_TH_TITLE', 'Title');
    define('_AM_TH_DESCRIPTION', 'Description');
    define('_AM_TH_CATEGORIES', 'Category');
    define('_AM_TH_DATE', 'Last update');
    // Photo Manager
    define('_AM_H3_FMT_PHOTOMANAGER', 'Photo Manager (%s)');
    define('_AM_TH_BATCHUPDATE', 'Update checked photos collectively');
    define('_AM_OPT_NOCHANGE', '- NO CHANGE -');
    define('_AM_JS_UPDATECONFIRM', 'The checked items will be updated. OK?');
    // Module Checker
    define('_AM_H3_FMT_MODULECHECKER', 'myAlbum-P checker (%s)');
    define('_AM_H4_ENVIRONMENT', 'Environment Check');
    define('_AM_MB_PHPDIRECTIVE', 'PHP directive');
    define('_AM_MB_BOTHOK', 'both ok');
    define('_AM_MB_NEEDON', 'need on');
    define('_AM_H4_TABLE', 'Table Check');
    define('_AM_MB_PHOTOSTABLE', 'Photos table');
    define('_AM_MB_DESCRIPTIONTABLE', 'Descriptions table');
    define('_AM_MB_CATEGORIESTABLE', 'Categories table');
    define('_AM_MB_VOTEDATATABLE', 'Votes table');
    define('_AM_MB_COMMENTSTABLE', 'Comments table');
    define('_AM_MB_NUMBEROFPHOTOS', 'Number of Photos');
    define('_AM_MB_NUMBEROFDESCRIPTIONS', 'Number of Descriptions');
    define('_AM_MB_NUMBEROFCATEGORIES', 'Number of Categories');
    define('_AM_MB_NUMBEROFVOTEDATA', 'Number of Votes');
    define('_AM_MB_NUMBEROFCOMMENTS', 'Number of Comments');
    define('_AM_H4_CONFIG', 'Config Check');
    define('_AM_MB_PIPEFORIMAGES', 'Pipe for images');
    define('_AM_MB_DIRECTORYFORPHOTOS', 'Directory for Photos');
    define('_AM_MB_DIRECTORYFORTHUMBS', 'Directory for Thumbnails');
    define('_AM_ERR_LASTCHAR', "Error: The last character should not be '/'");
    define('_AM_ERR_FIRSTCHAR', "Error: The first character should be '/'");
    define('_AM_ERR_PERMISSION', 'Error: At first create and chmod 777 this directory by FTP or Shell.');
    define('_AM_ERR_NOTDIRECTORY', 'Error: This is not a directory.');
    define('_AM_ERR_READORWRITE', 'Error: This directory is not writable nor readable. You should change the permission of the directory to 777.');
    define('_AM_ERR_SAMEDIR', 'Error: Photos Path should not be the same as Thumbs Path');
    define('_AM_LNK_CHECKGD2', "Check if 'GD2' works correctly with the GD bundled with your PHP");
    define('_AM_MB_CHECKGD2', "If the page linked from here don't display correctly, you should give up using your GD in Truecolor mode.");
    define('_AM_MB_GD2SUCCESS', 'Success!<br>Perhaps, you can use GD2 (truecolor) in this environment.');
    define('_AM_H4_PHOTOLINK', 'Photos & Thumbs Link Check');
    define('_AM_MB_NOWCHECKING', 'Now, checking.');
    define('_AM_FMT_PHOTONOTREADABLE', 'a main photo (%s) is not readable.');
    define('_AM_FMT_THUMBNOTREADABLE', 'a thumbnail (%s) is not readable.');
    define('_AM_FMT_NUMBEROFDEADPHOTOS', '%s dead photo files have been found.');
    define('_AM_FMT_NUMBEROFDEADTHUMBS', '%s thumbnails should be rebuilt.');
    define('_AM_FMT_NUMBEROFREMOVEDTMPS', '%s garbage files have been removed.');
    define('_AM_LINK_REDOTHUMBS', 'rebuild thumbnails');
    define('_AM_LINK_TABLEMAINTENANCE', 'maintain tables');
    // Redo Thumbnail
    define('_AM_H3_FMT_RECORDMAINTENANCE', 'myAlbum-P Photo Maintenance (%s)');
    define('_AM_FMT_CHECKING', 'Checking %s ...');
    define('_AM_FORM_RECORDMAINTENANCE', 'Maintenance of photos like recreating of thumbnails etc.');
    define('_AM_MB_FAILEDREADING', 'Failed reading.');
    define('_AM_MB_CREATEDTHUMBS', 'Created a thumbnail.');
    define('_AM_MB_BIGTHUMBS', 'Failed to create a thumbnail. Copied.');
    define('_AM_MB_SKIPPED', 'Skipped.');
    define('_AM_MB_SIZEREPAIRED', '(Repaired size fields of the record.)');
    define('_AM_MB_RECREMOVED', 'This record has been removed.');
    define('_AM_MB_PHOTONOTEXISTS', 'Main photo does not exist.');
    define('_AM_MB_PHOTORESIZED', 'Main photo was resized.');
    define('_AM_TEXT_RECORDFORSTARTING', 'Record number to start with');
    define('_AM_TEXT_NUMBERATATIME', 'Number of records to process at a time');
    define('_AM_LABEL_DESCNUMBERATATIME', 'Too large number may lead to server time out.');
    define('_AM_RADIO_FORCEREDO', 'Force recreating of thumbnails even if a thumbnail exists');
    define('_AM_RADIO_REMOVEREC', "Remove records that don't link to a main photo");
    define('_AM_RADIO_RESIZE', 'Resize bigger photos than the pixels specified in current preferences');
    define('_AM_MB_FINISHED', 'Finished');
    define('_AM_LINK_RESTART', 'Restart');
    define('_AM_SUBMIT_NEXT', 'Next');
    // Batch Register
    define('_AM_H3_FMT_BATCHREGISTER', 'myAlbum-P batch register (%s)');
    // GroupPerm Global
    define('_AM_ALBM_GROUPPERM_GLOBAL', 'Global Permissions');
    define('_AM_ALBM_GROUPPERM_GLOBALDESC', "Configure group's permissions for the whole module");
    define('_AM_ALBM_GPERMUPDATED', 'Permissions have been changed successfully');
    // Import
    define('_AM_H3_FMT_IMPORTTO', 'Importing images from another module to %s');
    define('_AM_FMT_IMPORTFROMMYALBUMP', 'Importing from %s as module type of myAlbum-P');
    define('_AM_FMT_IMPORTFROMIMAGEMANAGER', 'Importing from image manager in XOOPS');
    define('_AM_CB_IMPORTRECURSIVELY', 'Importing sub-categories recursively');
    define('_AM_RADIO_IMPORTCOPY', 'Copy images (comments will not be copied');
    define('_AM_RADIO_IMPORTMOVE', 'Move images (comments will be succeeded)');
    define('_AM_MB_IMPORTCONFIRM', 'About to start import. OK?');
    define('_AM_FMT_IMPORTSUCCESS', 'You have imported %s images');
    // Export
    define('_AM_H3_FMT_EXPORTTO', 'Exporting images from %s to another modules');
    define('_AM_FMT_EXPORTTOIMAGEMANAGER', 'Exporting to image manager in XOOPS');
    define('_AM_FMT_EXPORTIMSRCCAT', 'Source');
    define('_AM_FMT_EXPORTIMDSTCAT', 'Destination');
    define('_AM_CB_EXPORTRECURSIVELY', 'with images in its subcategories');
    define('_AM_CB_EXPORTTHUMB', 'Export thumbnails instead of main images');
    define('_AM_MB_EXPORTCONFIRM', 'About to start export. OK?');
    define('_AM_FMT_EXPORTSUCCESS', 'You have exported %s images');
    // Version 2.89
    define('_AM_ABOUT_MAKEDONATE', 'Make donation for MyAlbum-p');
    define('_AM_LABEL_OFF', 'Off');
    define('_AM_LABEL_ON', 'On');
    // Version 3.01
    define('_AM_H4_DIRECTORIES', 'Directories/Folders');
    define('_AM_LABEL_NOTHING', 'Nothing');
    define('_AM_TH_THUMBNAIL', 'Thumbnail');

    // server Info -----------------------------------------------
    define('_AM_WFDOWNLOADS_DOWN_IMAGEINFO', 'Server status');
    define('_AM_WFDOWNLOADS_DOWN_NOTSET', 'Upload path not set');
    define('_AM_WFDOWNLOADS_DOWN_SERVERPATH', 'Server path to XOOPS root: ');
    define('_AM_WFDOWNLOADS_DOWN_UPLOADPATH', 'Current upload path: ');

    define('_AM_WFDOWNLOADS_DOWN_METAVERSION', "<span style='font-weight: bold;'>Downloads meta version:</span> ");
    define('_AM_WFDOWNLOADS_DOWN_SPHPINI', "<span style='font-weight: bold;'>Information taken from PHP ini file:</span>");
    define('_AM_WFDOWNLOADS_DOWN_SAFEMODESTATUS', 'Safe mode status: ');
    define('_AM_WFDOWNLOADS_DOWN_REGISTERGLOBALS', 'Register globals: ');
    define('_AM_WFDOWNLOADS_DOWN_SERVERUPLOADSTATUS', 'Server uploads status: ');
    define('_AM_WFDOWNLOADS_DOWN_MAXUPLOADSIZE', 'Max upload size permitted (upload_max_filesize directive in php.ini): ');
    define('_AM_WFDOWNLOADS_DOWN_MAXPOSTSIZE', 'Max post size permitted (post_max_size directive in php.ini): ');
    define('_AM_WFDOWNLOADS_DOWN_SAFEMODEPROBLEMS', ' (This may cause problems)');
    define('_AM_WFDOWNLOADS_DOWN_GDLIBSTATUS', 'GD library support: ');
    define('_AM_WFDOWNLOADS_DOWN_GDLIBVERSION', 'GD Library version: ');
    define('_AM_WFDOWNLOADS_DOWN_GDON', "<span style='font-weight: bold;'>Enabled</span> (Thumbsnails available)");
    define('_AM_WFDOWNLOADS_DOWN_GDOFF', "<span style='font-weight: bold;'>Disabled</span> (No thumbnails available)");
    define('_AM_WFDOWNLOADS_DOWN_OFF', "<span style='font-weight: bold;'>OFF</span>");
    define('_AM_WFDOWNLOADS_DOWN_ON', "<span style='font-weight: bold;'>ON</span>");
    define('_AM_WFDOWNLOADS_DOWN_CATIMAGE', 'Category images');
    define('_AM_WFDOWNLOADS_DOWN_SCREENSHOTS', 'Screenshot images');
    define('_AM_WFDOWNLOADS_DOWN_MAINIMAGEDIR', 'Main images');
    define('_AM_WFDOWNLOADS_DOWN_FCATIMAGE', 'Category image path');
    define('_AM_WFDOWNLOADS_DOWN_FSCREENSHOTS', 'Screenshot image path');
    define('_AM_WFDOWNLOADS_DOWN_FMAINIMAGEDIR', 'Main image path');
    define('_AM_WFDOWNLOADS_DOWN_FUPLOADIMAGETO', 'Upload image: ');
    define('_AM_WFDOWNLOADS_DOWN_FUPLOADPATH', 'Upload path:');
    define('_AM_WFDOWNLOADS_DOWN_FUPLOADURL', 'Upload URL:');
    define('_AM_WFDOWNLOADS_DOWN_FOLDERSELECTION', 'Select upload destination');
    define('_AM_WFDOWNLOADS_DOWN_FSHOWSELECTEDIMAGE', 'Display selected image');
    define('_AM_WFDOWNLOADS_DOWN_FUPLOADIMAGE', 'Upload new image to selected destination');

    define('_AM_WFDOWNLOADS_DOWN_UPLOADPATHDSC', 'Note. Upload path *MUST* contain the full server path of your upload folder.');
}
