<?php

namespace DTM\version\registers;

use SPT\Application\IApp;

class Routing
{
    public static function registerEndpoints()
    {
        return [
            'versions'=>[
                'fnc' => [
                    'get' => 'version.version.list',
                    'post' => 'version.version.list',
                    'put' => 'version.version.update',
                    'delete' => 'version.version.delete'
                ],
                'permission' => [
                    'get' => ['version_manager', 'version_read'],
                    'post' => ['version_manager', 'version_read'],
                    'put' => ['version_manager', 'version_update'],
                    'delete' => ['version_manager', 'version_delete']
                ],
            ],
            'version' => [
                'fnc' => [
                    'get' => 'version.version.detail',
                    'post' => 'version.version.add',
                    'put' => 'version.version.update',
                    'delete' => 'version.version.delete'
                ],
                'parameters' => ['id'],
                'permission' => [
                    'get' =>  ['version_manager', 'version_read'],
                    'post' =>  ['version_manager', 'version_create'],
                    'put' =>  ['version_manager', 'version_update'],
                    'delete' =>  ['version_manager', 'version_delete']
                ],
            ],
            'version-feedback' => [
                'fnc' => [
                    'get' => 'version.feedback.list',
                    'post' => 'version.feedback.list',
                ],
                'parameters' => ['version_id'],
                'permission' => [
                    'get' =>  ['version_manager', 'version_read'],
                    'post' =>  ['version_manager', 'version_read'],
                ],
            ],
            'setting-version'=>[
                'fnc' => [
                    'get' => 'version.setting.version',
                    'post' => 'version.setting.versionSave',
                ],
                'permission' => [
                    'get' =>  ['version_manager'],
                    'post' =>  ['version_manager'],
                ],
            ],
        ];
    }
}
