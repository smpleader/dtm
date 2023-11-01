<?php

namespace DTM\note\registers;

use SPT\Application\IApp;

class Permission
{
    public static function registerAccess()
    {
        return [
            'note_manager'
        ];
    }
}
