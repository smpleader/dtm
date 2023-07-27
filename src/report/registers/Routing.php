<?php

namespace DTM\report\registers;

use SPT\Application\IApp;

class Routing
{
    public static function registerEndpoints()
    {
        return [
            'reports'=>[
                'fnc' => [
                    'get' => 'report.report.list',
                    'post' => 'report.report.list',
                    'put' => 'report.report.updateStatus',
                    'delete' => 'report.report.delete',
                ],
                'permission' => [
                    'get' => ['report_manager', 'report_read'],
                    'post' => ['report_manager', 'report_read'],
                    'put' => ['report_manager', 'report_update'],
                    'delete' => ['report_manager', 'report_delete']
                ],
            ],
            'new-report'=>[
                'fnc' => [
                    'get' => 'report.report.newform',
                    'post' => 'report.report.add',
                ],
                'parameters' => ['type'],
                'loadChildPlugin' => true,
                'permission' => [
                    'get' => ['report_manager', 'report_create'],
                    'post' => ['report_manager', 'report_create'],
                ],
            ],
            'report/detail'=>[
                'fnc' => [
                    'get' => 'report.report.detail',
                    'put' => 'report.report.update',
                ],
                'parameters' => ['id'],
                'loadChildPlugin' => true,
                'permission' => [
                    'get' => ['report_manager', 'report_update'],
                    'post' => ['report_manager', 'report_update'],
                ],
            ],
            'report/find-user'=>[
                'fnc' => [
                    'get' => 'report.report.findUser',
                ],
                'permission' => [
                    'get' => ['report_manager', 'report_update'],
                ],
            ],
        ];
    }
}
