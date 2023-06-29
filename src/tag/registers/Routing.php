<?php

namespace DTM\tag\registers;

use SPT\Application\IApp;

class Routing
{
    public static function registerEndpoints()
    {
        return [
            'tags' => [
                'fnc' => [
                    'get' => 'tag.tag.list',
                    'post' => 'tag.tag.list',
                    'put' => 'tag.tag.update',
                    'delete' => 'tag.tag.delete'
                ],
                'permission' => [
                    'get' => ['tag_manager', 'tag_read'],
                    'post' => ['tag_manager', 'tag_read'],
                    'put' => ['tag_manager', 'tag_update'],
                    'delete' => ['tag_manager', 'tag_delete']
                ],
            ],
            'tag/search' => [
                'fnc' => [
                    'get' => 'tag.tag.search',
                ],
            ],
            'tag' => [
                'fnc' => [
                    'get' => 'tag.tag.detail',
                    'post' => 'tag.tag.add',
                    'put' => 'tag.tag.update',
                    'delete' => 'tag.tag.delete'
                ],
                'parameters' => ['id'],
                'permission' => [
                    'get' => ['tag_manager', 'tag_read'],
                    'post' => ['tag_manager', 'tag_create'],
                    'put' => ['tag_manager', 'tag_update'],
                    'delete' => ['tag_manager', 'tag_delete']
                ],
            ],
        ];
    }
}
