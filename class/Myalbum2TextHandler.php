<?php

namespace XoopsModules\Myalbum;



require_once \dirname(__DIR__) . '/include/read_configs.php';


/**
 * Class Myalbum2TextHandler
 */
class Myalbum2TextHandler extends TextHandler
{
    /**
     * Myalbum2TextHandler constructor.
     * @param null|\XoopsDatabase $db
     */
    public function __construct(\XoopsDatabase $db = null)
    {
        parent::__construct($db);
    }
}
