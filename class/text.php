<?php namespace XoopsModules\Myalbum;

use XoopsModules\Myalbum;

// defined('XOOPS_ROOT_PATH') || die('Restricted access');

include  dirname(__DIR__) . '/include/read_configs.php';

/**
 * Class for Blue Room Xcenter
 *
 * @author    Simon Roberts <simon@xoops.org>
 * @copyright copyright (c) 2009-2003 XOOPS.org
 * @package   kernel
 */
class Text extends \XoopsObject
{
    /**
     * MyalbumText constructor.
     * @param null $id
     */
    public function __construct($id = null)
    {
        $this->initVar('lid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('description', XOBJ_DTYPE_OTHER, null, false, 16 * 1024 * 1024 * 1024);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $ret                = parent::toArray();
        $ret['description'] = $GLOBALS['myts']->displayTarea($ret['description'], 1, 1, 1, 1, 1, 1);

        return $ret;
    }
}
