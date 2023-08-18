<?php

namespace DTM\media\registers;

use SPT\Application\IApp;

class Permission
{
    public static function registerAccess()
    {
        return [
            'tag_manager', 'tag_read', 'tag_create', 'tag_update', 'tag_delete' 
        ];
    }
}
