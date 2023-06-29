<?php

namespace DTM\version\registers;

use SPT\Application\IApp;

class Permission
{
    public static function registerAccess()
    {
        return [
            'version_manager', 'version_read', 'version_create', 'version_update', 'version_delete' 
        ];
    }
}
