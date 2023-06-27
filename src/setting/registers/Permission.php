<?php

namespace DTM\setting\registers;

use SPT\Application\IApp;

class Permission
{
    public static function registerAccess()
    {
        return [
            'setting_manager',
        ];
    }
}
