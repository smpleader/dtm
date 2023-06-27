<?php

namespace DTM\milestone\registers;

use SPT\Application\IApp;

class Permission
{
    public static function registerAccess()
    {
        return [
            'milestone_manager', 'milestone_read', 'milestone_create', 'milestone_update', 'milestone_delete',
            'request_manager', 'request_read', 'request_create', 'request_update', 'request_delete' 
        ];
    }
}
