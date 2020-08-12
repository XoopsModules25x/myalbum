<?php

namespace XoopsModules\Myalbum;

require_once \dirname(__DIR__) . '/include/read_configs.php';

 /**
 * Class Myalbum0CommentsHandler
 */
class Myalbum0CommentsHandler extends CommentsHandler
{
    /**
     * Myalbum0CommentsHandler constructor.
     * @param null|\XoopsDatabase $db
     */
    public function __construct(\XoopsDatabase $db = null)
    {
        parent::__construct($db);
    }
}
