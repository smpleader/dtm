<?php

namespace DTM\report\registers;

use SPT\Application\IApp;

class Permission
{
    public static function registerAccess()
    {
        return [
            'report_manager', 'report_read', 'report_create', 'report_update', 'report_delete' 
        ];
    }
}
