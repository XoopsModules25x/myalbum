<?php
// defined('XOOPS_ROOT_PATH') || die('Restricted access');

include __DIR__ . '/../include/read_configs.php';

/**
 * Class for Blue Room Xcenter
 *
 * @author    Simon Roberts <simon@xoops.org>
 * @copyright copyright (c) 2009-2003 XOOPS.org
 * @package   kernel
 */
class MyalbumText extends XoopsObject
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
    /**
     * MyalbumTextHandler constructor.
     * @param null|\XoopsDatabase $db
     */
    public function __construct(\XoopsDatabase $db)
    {
        $this->db = $db;
        parent::__construct($db, $GLOBALS['table_text'], 'MyalbumText', 'lid', 'description');
    }

    /**
     * @return mixed
     */
    public function getBytes()
    {
        $sql = 'SELECT SUM(LENGTH(`description`)) AS `bytes` FROM ' . $GLOBALS['xoopsDB']->prefix($GLOBALS['table_text']);
        list($bytes) = $GLOBALS['xoopsDB']->fetchRow($GLOBALS['xoopsDB']->queryF($sql));

        return $bytes;
    }
}

/**
 * Class Myalbum0TextHandler
 */
class Myalbum0TextHandler extends MyalbumTextHandler
{
    /**
     * Myalbum0TextHandler constructor.
     * @param null|\XoopsDatabase $db
     */
    public function __construct(\XoopsDatabase $db)
    {
        parent::__construct($db);
    }
}

/**
 * Class Myalbum1TextHandler
 */
class Myalbum1TextHandler extends MyalbumTextHandler
{
    /**
     * Myalbum1TextHandler constructor.
     * @param null|\XoopsDatabase $db
     */
    public function __construct(\XoopsDatabase $db)
    {
        parent::__construct($db);
    }
}

/**
 * Class Myalbum2TextHandler
 */
class Myalbum2TextHandler extends MyalbumTextHandler
{
    /**
     * Myalbum2TextHandler constructor.
     * @param null|\XoopsDatabase $db
     */
    public function __construct(\XoopsDatabase $db)
    {
        parent::__construct($db);
    }
}
