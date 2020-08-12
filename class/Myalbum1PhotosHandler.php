<?php

namespace XoopsModules\Myalbum;



require_once \dirname(__DIR__) . '/include/read_configs.php';

/**
 * Class Myalbum1PhotosHandler
 */
class Myalbum1PhotosHandler extends PhotosHandler
{
    /**
     * @param null|\XoopsDatabase $db
     */
    public function __construct(\XoopsDatabase $db = null)
    {
        parent::__construct($db);
    }
}
