<?php

if (!defined('XOOPS_ROOT_PATH')) {
    exit();
}

include (dirname(__DIR__)) . '/include/read_configs.php';

/**
 * Class for Blue Room Xcenter
 *
 * @author    Simon Roberts <simon@xoops.org>
 * @copyright copyright (c) 2009-2003 XOOPS.org
 * @package   kernel
 */
class MyalbumVotedata extends XoopsObject
{
    /**
     * MyalbumVotedata constructor.
     * @param null $id
     */
    public function __construct($id = null)
    {
        $this->initVar('ratingid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('lid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('ratinguser', XOBJ_DTYPE_INT, null, false);
        $this->initVar('rating', XOBJ_DTYPE_INT, null, false);
        $this->initVar('ratinghostname', XOBJ_DTYPE_INT, null, false);
        $this->initVar('ratingtimestamp', XOBJ_DTYPE_INT, null, false);
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
class MyalbumVotedataHandler extends XoopsPersistableObjectHandler
{
    /**
     * MyalbumVotedataHandler constructor.
     * @param null|object $db
     */
    public function __construct(&$db)
    {
        $this->db = $db;
        parent::__construct($db, $GLOBALS['table_votedata'], 'MyalbumVotedata', "ratingid", "lid");
    }
}

/**
 * Class Myalbum0VotedataHandler
 */
class Myalbum0VotedataHandler extends MyalbumVotedataHandler
{
    /**
     * Myalbum0VotedataHandler constructor.
     * @param null|object $db
     */
    public function __construct(&$db)
    {
        parent::__construct($db);
    }
}

/**
 * Class Myalbum1VotedataHandler
 */
class Myalbum1VotedataHandler extends MyalbumVotedataHandler
{
    /**
     * Myalbum1VotedataHandler constructor.
     * @param null|object $db
     */
    public function __construct(&$db)
    {
        parent::__construct($db);
    }
}

/**
 * Class Myalbum2VotedataHandler
 */
class Myalbum2VotedataHandler extends MyalbumVotedataHandler
{
    /**
     * Myalbum2VotedataHandler constructor.
     * @param null|object $db
     */
    public function __construct(&$db)
    {
        parent::__construct($db);
    }
}
