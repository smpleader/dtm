<?php

namespace DTM\shortcut\registers;

use SPT\Application\IApp;

class Routing
{
    public static function registerEndpoints()
    {
        return [
            'shortcuts' => [
                'fnc' => [
                    'get' => 'shortcut.shortcut.list',
                ],
            ],
            'shortcut' => [
                'fnc' => [
                    // 'get' => 'shortcut.shortcut.detail',
                    'post' => 'shortcut.shortcut.add',
                    'put' => 'shortcut.shortcut.update',
                    'delete' => 'shortcut.shortcut.delete',
                ],
                'parameters' => ['id'],
            ],
        ];
    }
}
