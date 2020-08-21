<?php

namespace XoopsModules\Myalbum;

require_once \dirname(__DIR__) . '/include/read_configs.php';

 /**
 * Class Myalbum2CommentsHandler
 */
class Myalbum2CommentsHandler extends CommentsHandler
{
    /**
     * Myalbum2CommentsHandler constructor.
     * @param null|\XoopsDatabase $db
     */
    public function __construct(\XoopsDatabase $db = null)
    {
        parent::__construct($db);
    }
}
