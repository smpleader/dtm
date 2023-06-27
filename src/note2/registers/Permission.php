<?php

namespace DTM\note2\registers;

use SPT\Application\IApp;

class Permission
{
    public static function registerAccess()
    {
        return [
            'note_manager', 'note_read', 'note_create', 'note_update', 'note_delete' 
        ];
    }
}
