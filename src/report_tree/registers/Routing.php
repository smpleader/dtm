<?php

namespace DTM\report_tree\registers;

use SPT\Application\IApp;

class Routing
{
    public static function registerEndpoints()
    {
        return [
            // 'tree-phps' => [
            //     'fnc' => [
            //         'get' => 'report_tree.treediagram.list',
            //         'post' => 'report_tree.treediagram.list',
            //         'put' => 'report_tree.treediagram.update',
            //         'delete' => 'report_tree.treediagram.delete'
            //     ],
            //     'permission' => [
            //         'get' => ['treephp_manager', 'treephp_read'],
            //         'post' => ['treephp_manager', 'treephp_read'],
            //         'put' => ['treephp_manager', 'treephp_update'],
            //         'delete' => ['treephp_manager', 'treephp_delete']
            //     ],
            // ],
            'report/find-request' => [
                'fnc' => [
                    'get' => 'report_tree.ajax.findrequest',
                ],
                'parameters' => ['id'],
            ],
        ];
    }
}
