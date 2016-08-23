<?php

define('_ALBM_DIRNAME', basename(dirname(__DIR__)));
define('_ALBM_URL', XOOPS_URL . '/modules/' . _ALBM_DIRNAME);
define('_ALBM_PATH', XOOPS_ROOT_PATH . '/modules/' . _ALBM_DIRNAME);
define('_ALBM_IMAGES_URL', _ALBM_URL . '/assets/images');
define('_ALBM_ADMIN_URL', _ALBM_URL . '/admin');
define('_ALBM_ADMIN_PATH', _ALBM_PATH . '/admin/index.php');
define('_ALBM_ROOT_PATH', $GLOBALS['xoops']->path('modules/' . _ALBM_DIRNAME));
define('_ALBM_AUTHOR_LOGOIMG', _ALBM_URL . '/assets/images/logo.png');
define('_ALBM_UPLOAD_URL', XOOPS_UPLOAD_URL . '/' . _ALBM_DIRNAME); // WITHOUT Trailing slash
define('_ALBM_UPLOAD_PATH', XOOPS_UPLOAD_PATH . '/' . _ALBM_DIRNAME); // WITHOUT Trailing slash
