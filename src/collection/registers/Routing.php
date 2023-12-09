<?php

namespace DTM\collection\registers;

use SPT\Application\IApp;

class Routing
{
    public static function registerEndpoints()
    {
        return [
            'collections' => [
                'fnc' => [
                    'get' => 'collection.collection.list',
                    'post' => 'collection.collection.list',
                    'put' => 'collection.collection.update',
                    'delete' => 'collection.collection.delete'
                ],
            ],
            'collection/edit' => [
                'fnc' => [
                    'get' => 'collection.collection.detail',
                    'post' => 'collection.collection.add',
                    'put' => 'collection.collection.update',
                    'delete' => 'collection.collection.delete',
                ],
                'parameters' => ['id'],
            ],
            'collection' => [
                'fnc' => [
                    'get' => 'collection.collection.filter',
                    'post' => 'collection.collection.filter',
                ],
                'parameters' => ['filter_name'],
            ],
        ];
    }
}
