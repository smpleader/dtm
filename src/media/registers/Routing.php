<?php

namespace DTM\media\registers;

use SPT\Application\IApp;

class Routing
{
    public static function registerEndpoints()
    {
        return [
            'admin/media' => [
                'fnc' => [
                    'get' => 'media.media.list',
                    'post' => 'media.media.list',
                    'delete' => 'media.media.delete',
                ],
                'permission' => [
                    'get' => ['media_manager', 'media_read'],
                    'post' => ['media_manager', 'media_read'],
                    'delete' => ['media_manager', 'media_delete'],
                ],
            ],
            'admin/media/upload' => [
                'fnc' => [
                    'post' => 'media.media.upload',
                ],
            ],
            'admin/media/ajax-upload' => [
                'fnc' => [
                    'post' => 'media.ajax.upload',
                ],
            ],
        ];
    }
}
