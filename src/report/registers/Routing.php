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
            'report'=>[
                'fnc' => [
                    'put' => 'report.report.update',
                ],
                'parameters' => ['id'],
                'permission' => [
                    'put' => ['report_manager', 'report_update'],
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
