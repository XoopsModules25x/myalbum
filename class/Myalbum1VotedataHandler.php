<?php

namespace XoopsModules\Myalbum;



require_once \dirname(__DIR__) . '/include/read_configs.php';


/**
 * Class Myalbum1VotedataHandler
 */
class Myalbum1VotedataHandler extends VotedataHandler
{
    /**
     * Myalbum1VotedataHandler constructor.
     * @param null|\XoopsDatabase $db
     */
    public function __construct(\XoopsDatabase $db = null)
    {
        parent::__construct($db);
    }
}
