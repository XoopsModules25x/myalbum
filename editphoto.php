<?php
// ------------------------------------------------------------------------- //
//                      myAlbum-P - XOOPS photo album                        //
//                        <http://www.peak.ne.jp/>                           //
// ------------------------------------------------------------------------- //

include __DIR__ . '/header.php';

$lid = empty($_GET['lid']) ? 0 : (int)$_GET['lid'];

/** @var MyalbumPhotosHandler $photosHandler */
$photosHandler = xoops_getModuleHandler('photos', $GLOBALS['mydirname']);
/** @var MyalbumTextHandler $textHandler */
$textHandler   = xoops_getModuleHandler('text', $GLOBALS['mydirname']);
if (!$photo_obj = $photosHandler->get($lid)) {
    redirect_header('index.php', 2, _ALBM_NOMATCH);
}
$submitter = $photo_obj->getVar('submitted');

if ($global_perms & GPERM_EDITABLE) {
    if ($my_uid != $submitter && !$isadmin) {
        redirect_header($mod_url, 3, _NOPERM);
    }
} else {
    redirect_header($mod_url, 3, _NOPERM);
}

// Do Delete
if (!empty($_POST['do_delete'])) {
    if (!($global_perms & GPERM_DELETABLE)) {
        redirect_header($mod_url, 3, _NOPERM);
    }

    // anti-CSRF
    if (!XoopsSecurity::checkReferer()) {
        die('XOOPS_URL is not included in your REFERER');
    }

    // get and check lid is valid
    if ($lid < 1) {
        die('Invalid photo id.');
    }

    $photosHandler->delete($photo_obj);

    redirect_header($mod_url, 3, _ALBM_DELETINGPHOTO);
}

// Confirm Delete
if (!empty($_POST['conf_delete'])) {
    if (!($global_perms & GPERM_DELETABLE)) {
        redirect_header($mod_url, 3, _NOPERM);
    }

    include XOOPS_ROOT_PATH . '/include/cp_functions.php';
    include_once $GLOBALS['xoops']->path('header.php');

    $ext = $photo_obj->getVar('ext');
    if (!in_array(strtolower($ext), $myalbum_normal_exts)) {
        $ext = 'gif';
    }

    echo '
    <h4>' . _ALBM_PHOTODEL . "</h4>
    <div>
        <img src='" . $photo_obj->getThumbsURL() . "' />
        <br>
        <form action='" . $photo_obj->getEditURL() . "' method='post'>
            <input type='submit' name='do_delete' value='" . _YES . "' />
            <input type='submit' name='cancel_delete' value=" . _NO . " />
        </form>
    </div>
    \n";

    include $GLOBALS['xoops']->path('footer.php');
    exit;
}

// Do Modify
if (!empty($_POST['submit'])) {

    // anti-CSRF
    if (!XoopsSecurity::checkReferer()) {
        die('XOOPS_URL is not included in your REFERER');
    }

    if (empty($_POST['submitter'])) {
        $submitter = $my_uid;
    } else {
        $submitter = (int)$_POST['submitter'];
    }

    // status change
    if ($isadmin) {
        $valid = empty($_POST['valid']) ? 0 : (int)$_POST['valid'];
        if (empty($_POST['old_status'])) {
            if ($valid == 0) {
                $valid = null;
            } else {
                $valid = 1;
            }
        } else {
            if ($valid == 0) {
                $valid = 1;
            } else {
                $valid = 2;
            }
        }
    } else {
        $valid = 2;
    }

    $cid = empty($_POST['cid']) ? 0 : (int)$_POST['cid'];

    // Check if upload file name specified
    $field = $_POST['xoops_upload_file'][0];
    if (empty($field) || $field == '') {
        die('UPLOAD error: file name not specified');
    }
    $field = $_POST['xoops_upload_file'][0];

    // Check if file uploaded
    if ($_FILES[$field]['tmp_name'] != '' && $_FILES[$field]['tmp_name'] !== 'none') {
        if ($GLOBALS['myalbumModuleConfig']['myalbum_canresize']) {
            $uploader = new MyXoopsMediaUploader($GLOBALS['photos_dir'], explode('|', $GLOBALS['myalbumModuleConfig']['myalbum_allowedmime']), $GLOBALS['myalbumModuleConfig']['myalbum_fsize'], null, null,
                                                 explode('|', $GLOBALS['myalbumModuleConfig']['myalbum_allowedexts']));
        } else {
            $uploader = new MyXoopsMediaUploader($GLOBALS['photos_dir'], explode('|', $GLOBALS['myalbumModuleConfig']['myalbum_allowedmime']), $GLOBALS['myalbumModuleConfig']['myalbum_fsize'], $GLOBALS['myalbumModuleConfig']['myalbum_width'],
                                                 $GLOBALS['myalbumModuleConfig']['myalbum_height'], explode('|', $GLOBALS['myalbumModuleConfig']['myalbum_allowedexts']));
        }

        $uploader->setPrefix('tmp_');
        if ($uploader->fetchMedia($field) && $uploader->upload()) {
            // remove old file.
            $ext = $photo_obj->getVar('ext');
            @unlink($GLOBALS['photos_dir'] . "/$lid.$ext");
            @unlink($GLOBALS['thumbs_dir'] . "/$lid.$ext");
            @unlink($GLOBALS['thumbs_dir'] . "/$lid.gif");

            // The original file name will be the title if title is empty
            if (trim($_POST['title']) === '') {
                $_POST['title'] = $uploader->getMediaName();
            }

            $title     = $GLOBALS['myts']->stripSlashesGPC($_POST['title']);
            $desc_text = $GLOBALS['myts']->stripSlashesGPC($_POST['desc_text']);
            $date      = time();
            $tmp_name  = $uploader->getSavedFileName();
            $ext       = substr(strrchr($tmp_name, '.'), 1);

            MyalbumUtilities::editPhoto($GLOBALS['photos_dir'] . "/$tmp_name", $GLOBALS['photos_dir'] . "/$lid.$ext");
            $dim = getimagesize($GLOBALS['photos_dir'] . "/$lid.$ext");
            if (!$dim) {
                $dim = array(0, 0);
            }

            if (!MyalbumUtilities::createThumb($GLOBALS['photos_dir'] . "/$lid.$ext", $lid, $ext)) {
                redirect_header('editphoto.php?lid=$lid', 10, _ALBM_FILEERROR);
            }

            MyalbumUtilities::updatePhoto($lid, $cid, $title, $desc_text, $valid, $ext, $dim[0], $dim[1]);
            exit;
        } else {
            $uploader->getErrors(true);
            include_once $GLOBALS['xoops']->path('header.php');
            echo "<p><strong>::Errors occured::</strong></p>\n";
            echo $uploader->getErrors(true);
            include_once $GLOBALS['xoops']->path('footer.php');
            exit;
        }
    } else { //update without file upload
        // Check if title is blank
        if (trim($_POST['title']) === '') {
            $_POST['title'] = 'no title';
        }
        $title     = $GLOBALS['myts']->stripSlashesGPC($_POST['title']);
        $desc_text = $GLOBALS['myts']->stripSlashesGPC($_POST['desc_text']);
        $cid       = (int)$_POST['cid'];
        $ext       = $_POST['ext'];
        if ($GLOBALS['myalbumModuleConfig']['tag']) {
            /** @var TagTagHandler $tagHandler */
            $tagHandler = xoops_getModuleHandler('tag', 'tag');
            $tagHandler->updateByItem($_POST['tags'], $lid, $GLOBALS['myalbumModule']->getVar('dirname'), $cid);
        }
        MyalbumUtilities::updatePhoto($lid, $cid, $title, $desc_text, $valid);
        exit;
    }
}
if (!strpos($photo_obj->getEditURL(), $_SERVER['REQUEST_URI'])) {
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $photo_obj->getEditURL());
    exit;
}

// Editing Display
include_once $GLOBALS['xoops']->path('header.php');
MyalbumPreview::header();

// Display
$photo_for_tpl = MyalbumPreview::getArrayForPhotoAssign($photo_obj);
$tpl           = new XoopsTpl();
include __DIR__ . '/include/assign_globals.php';
$tpl->assign($myalbum_assign_globals);
$tpl->assign('photo', $photo_for_tpl);
echo "<table class='outer' style='width:100%;'>";
$tpl->display("db:{$moduleDirName }_photo_in_list.tpl");
echo "</table>\n";

// Show the form
$form = new XoopsThemeForm(_ALBM_PHOTOEDITUPLOAD, 'uploadphoto', XOOPS_URL . '/modules/' . $moduleDirName . "/editphoto.php?lid=$lid");
$form->setExtra("enctype='multipart/form-data'");

$title_text = new XoopsFormText(_ALBM_PHOTOTITLE, 'title', 50, 255, $photo_obj->getVar('title'));

$cat_select = new XoopsFormLabel('', $GLOBALS['cattree']->makeSelBox('cid', 'title', '-', $photo_obj->getVar('cid')));

$cat_link = new XoopsFormLabel("<a href='javascript:location.href=\"" . XOOPS_URL . '/modules/' . $moduleDirName . "/viewcat.php?cid=\"+document.uploadphoto.cid.value;'>" . _GO . '</a>');
$cat_tray = new XoopsFormElementTray(_ALBM_PHOTOCAT, '&nbsp;');
$cat_tray->addElement($cat_select);
$cat_tray->addElement($cat_link);

$text                   = $textHandler->get($lid);
$html_configs           = array();
$html_configs['name']   = 'desc_text';
$html_configs['value']  = $text->getVar('description');
$html_configs['rows']   = 35;
$html_configs['cols']   = 60;
$html_configs['width']  = '100%';
$html_configs['height'] = '400px';
$html_configs['editor'] = $GLOBALS['myalbumModuleConfig']['editor'];
$desc_tarea             = new XoopsFormEditor(_ALBM_PHOTODESC, $html_configs['name'], $html_configs);

$file_form = new XoopsFormFile(_ALBM_SELECTFILE, 'photofile', $myalbum_fsize);
$file_form->setExtra("size='70'");

if ($myalbum_canrotate) {
    $rotate_radio = new XoopsFormRadio(_ALBM_RADIO_ROTATETITLE, 'rotate', 'rot0');
    $rotate_radio->addOption('rot0', _ALBM_RADIO_ROTATE0 . ' &nbsp; ');
    $rotate_radio->addOption('rot90', "<img src='assets/images/icon_rotate90.gif' alt='" . _ALBM_RADIO_ROTATE90 . "' title='" . _ALBM_RADIO_ROTATE90 . "' /> &nbsp; ");
    $rotate_radio->addOption('rot180', "<img src='assets/images/icon_rotate180.gif' alt='" . _ALBM_RADIO_ROTATE180 . "' title='" . _ALBM_RADIO_ROTATE180 . "' /> &nbsp; ");
    $rotate_radio->addOption('rot270', "<img src='assets/images/icon_rotate270.gif' alt='" . _ALBM_RADIO_ROTATE270 . "' title='" . _ALBM_RADIO_ROTATE270 . "' /> &nbsp; ");
}

$op_hidden      = new XoopsFormHidden('op', 'submit');
$counter_hidden = new XoopsFormHidden('fieldCounter', 1);
$status_hidden  = new XoopsFormHidden('old_status', $photo_for_tpl['status']);
$valid_or_not   = empty($photo['status']) ? 0 : 1;
$valid_box      = new XoopsFormCheckBox(_ALBM_VALIDPHOTO, 'valid', array($valid_or_not));
$valid_box->addOption('1', '&nbsp;');

$submit_button  = new XoopsFormButton('', 'submit', _SUBMIT, 'submit');
$preview_button = new XoopsFormButton('', 'preview', _PREVIEW, 'submit');
$reset_button   = new XoopsFormButton('', 'reset', _CANCEL, 'reset');
$submit_tray    = new XoopsFormElementTray('');
$submit_tray->addElement($preview_button);
$submit_tray->addElement($submit_button);
$submit_tray->addElement($reset_button);
if ($global_perms & GPERM_DELETABLE) {
    $delete_button = new XoopsFormButton('', 'conf_delete', _DELETE, 'submit');
    $submit_tray->addElement($delete_button);
}

$form->addElement($title_text);
$form->addElement($desc_tarea);
$form->addElement($cat_tray);
$form->addElement($file_form);
if ($GLOBALS['myalbumModuleConfig']['tag']) {
    $form->addElement(new TagFormTag('tags', 35, 255, $lid));
}
if ($myalbum_canrotate) {
    $form->addElement($rotate_radio);
}
$form->addElement($counter_hidden);
$form->addElement($op_hidden);
if ($isadmin) {
    $form->addElement($valid_box);
    $form->addElement($status_hidden);
}
$form->addElement($submit_tray);
$form->display();

MyalbumPreview::footer();

include XOOPS_ROOT_PATH . '/footer.php';
