<?php
if (!defined('XOOPS_ROOT_PATH')) {
    exit();
}

include dirname(__DIR__) . '/include/read_configs.php';

/**
 * Class for Blue Room Xcenter
 *
 * @author    Simon Roberts <simon@xoops.org>
 * @copyright copyright (c) 2009-2003 XOOPS.org
 * @package   kernel
 */
class MyalbumText extends XoopsObject
{

    function __construct($id = null)
    {
        $this->initVar('lid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('description', XOBJ_DTYPE_OTHER, null, false, 16 * 1024 * 1024 * 1024);
    }

    function toArray()
    {
        $ret                = parent::toArray();
        $ret['description'] = $GLOBALS['myts']->displayTarea($ret['description'], 1, 1, 1, 1, 1, 1);;

        return $ret;
    }
}

/**
 * XOOPS policies handler class.
 * This class is responsible for providing data access mechanisms to the data source
 * of XOOPS user class objects.
 *
 * @author  Simon Roberts <simon@chronolabs.coop>
 * @package kernel
 */
class MyalbumTextHandler extends XoopsPersistableObjectHandler
{
    function __construct(&$db)
    {
        $this->db = $db;
        parent::__construct($db, $GLOBALS['table_text'], 'MyalbumText', 'lid', 'description');
    }

    function getBytes()
    {
        $sql = 'SELECT SUM(LENGTH(`description`)) as `bytes` FROM ' . $GLOBALS['xoopsDB']->prefix($GLOBALS['table_text']);
        list($bytes) = $GLOBALS['xoopsDB']->fetchRow($GLOBALS['xoopsDB']->queryF($sql));

        return $bytes;
    }
}

class Myalbum0TextHandler extends MyalbumTextHandler
{
    function __construct(&$db)
    {
        parent::__construct($db);
    }
}

class Myalbum1TextHandler extends MyalbumTextHandler
{
    function __construct(&$db)
    {
        parent::__construct($db);
    }
}

class Myalbum2TextHandler extends MyalbumTextHandler
{
    function __construct(&$db)
    {
        parent::__construct($db);
    }
}
