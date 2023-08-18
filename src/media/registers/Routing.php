<?php

namespace DTM\media\registers;

use SPT\Application\IApp;

class Routing
{
    public static function registerEndpoints()
    {
        return [
            'media' => [
                'fnc' => [
                    'get' => 'media.media.list',
                    'post' => 'media.media.list',
                ],
                'permission' => [
                    'get' => ['media_manager', 'media_read'],
                    'post' => ['media_manager', 'media_read'],
                    'put' => ['media_manager', 'media_update'],
                    'delete' => ['media_manager', 'media_delete']
                ],
            ],
            'media/upload' => [
                'fnc' => [
                    'post' => 'media.media.upload',
                ],
            ],
            'media/ajax-upload' => [
                'fnc' => [
                    'post' => 'media.ajax.upload',
                ],
            ],
            'media/list' => [
                'fnc' => [
                    'post' => 'media.ajax.list',
                ],
            ],
        ];
    }
}
