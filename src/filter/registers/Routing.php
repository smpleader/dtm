<?php

namespace DTM\filter\registers;

use SPT\Application\IApp;

class Routing
{
    public static function registerEndpoints()
    {
        return [
            'collections' => [
                'fnc' => [
                    'get' => 'filter.filter.list',
                    'post' => 'filter.filter.list',
                    'put' => 'filter.filter.update',
                    'delete' => 'filter.filter.delete'
                ],
            ],
            'collection/edit' => [
                'fnc' => [
                    'get' => 'filter.filter.detail',
                    'post' => 'filter.filter.add',
                    'put' => 'filter.filter.update',
                    'delete' => 'filter.filter.delete',
                ],
                'parameters' => ['id'],
            ],
            'collection' => [
                'fnc' => [
                    'get' => 'filter.filter.filter',
                    'post' => 'filter.filter.filter',
                ],
                'parameters' => ['filter_name'],
            ],
        ];
    }
}
