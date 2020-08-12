<?php

namespace XoopsModules\Myalbum;



require_once \dirname(__DIR__) . '/include/read_configs.php';


/**
 * Class Myalbum0TextHandler
 */
class Myalbum0TextHandler extends TextHandler
{
    /**
     * Myalbum0TextHandler constructor.
     * @param null|\XoopsDatabase $db
     */
    public function __construct(\XoopsDatabase $db = null)
    {
        parent::__construct($db);
    }
}
