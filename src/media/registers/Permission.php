<?php

namespace DTM\media\registers;

use SPT\Application\IApp;

class Permission
{
    public static function registerAccess()
    {
        return [
            'media_manager', 'media_read', 'media_create', 'media_update', 'media_delete' 
        ];
    }
}
