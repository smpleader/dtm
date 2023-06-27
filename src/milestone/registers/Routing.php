<?php

namespace DTM\milestone\registers;

use SPT\Application\IApp;

class Routing
{
    public static function registerEndpoints()
    {
        return [
            // Endpoint Milestones
            'milestones'=>[
                'fnc' => [
                    'get' => 'milestone.milestone.list',
                    'post' => 'milestone.milestone.list',
                    'put' => 'milestone.milestone.update',
                    'delete' => 'milestone.milestone.delete'
                ],
                'permission' => [
                    'get' => ['milestone_manager', 'milestone_read'],
                    'post' => ['milestone_manager', 'milestone_read'],
                    'put' => ['milestone_manager', 'milestone_update'],
                    'delete' => ['milestone_manager', 'milestone_delete']
                ],
            ],
            'requests' => [
                'fnc' => [
                    'get' => 'milestone.request.list',
                    'post' => 'milestone.request.list',
                    'put' => 'milestone.request.update',
                    'delete' => 'milestone.request.delete'
                ],
                'parameters' => ['milestone_id'],
                'permission' => [
                    'get' => ['request_manager', 'request_read'],
                    'post' => ['request_manager', 'request_read'],
                    'put' => ['request_manager', 'request_update'],
                    'delete' => ['request_manager', 'request_delete']
                ],
            ],
            'request/find-user'=>[
                'fnc' => [
                    'get' => 'milestone.request.findUser',
                ],
                'permission' => [
                    'get' => ['request_manager', 'request_manager'],
                ],
            ],
            'request' => [
                'fnc' => [
                    'get' => 'milestone.request.detail',
                    'post' => 'milestone.request.add',
                    'put' => 'milestone.request.update',
                    'delete' => 'milestone.request.delete'
                ],
                'parameters' => ['milestone_id','id'],
                'permission' => [
                    'get' => ['request_manager', 'request_read'],
                    'post' => ['request_manager', 'request_create'],
                    'put' => ['request_manager', 'request_update'],
                    'delete' => ['request_manager', 'request_delete']
                ],

            ],
            'tasks' => [
                'fnc' => [
                    'post' => 'milestone.task.list',
                    'put' => 'milestone.task.update',
                    'delete' => 'milestone.task.delete'
                ],
                'parameters' => ['request_id'],
            ],
            'task' => [
                'fnc' => [
                    'get' => 'milestone.task.detail',
                    'post' => 'milestone.task.add',
                    'put' => 'milestone.task.update',
                    'delete' => 'milestone.task.delete'
                ],
                'parameters' => ['request_id', 'id'],
            ],
            'relate-notes' => [
                'fnc' => [
                    'post' => 'milestone.note.list',
                    'put' => 'milestone.note.update',
                    'delete' => 'milestone.note.delete'
                ],
                'parameters' => ['request_id'],
            ],
            'get-notes' => [
                'fnc' => [
                    'post' => 'milestone.note.getNote',
                ],
                'parameters' => ['request_id'],
            ],
            'detail-request' => [
                'fnc' => [
                    'get' => 'milestone.request.detail_request',
                ],
                'parameters' => ['request_id'],
            ],
            'document/version' => [
                'fnc' => [
                    'get' => 'milestone.documenthistory.detail',
                    'post' => 'milestone.documenthistory.rollback',
                    'delete' => 'milestone.documenthistory.delete',
                ],
                'parameters' => ['id'],
            ],
            'document' => [
                'fnc' => [
                    'post' => 'milestone.document.save',
                ],
                'parameters' => ['request_id'],
            ],
            'get-history' => [
                'fnc' => [
                    'post' => 'milestone.document.getHistory',
                ],
                'parameters' => ['request_id'],
            ],
            'get-comment' => [
                'fnc' => [
                    'post' => 'milestone.document.getComment',
                ],
                'parameters' => ['request_id'],
            ],
            'discussion' => [
                'fnc' => [
                    'post' => 'milestone.discussion.add',
                ],
                'parameters' => ['request_id'],
            ],
            'relate-note/update-alias' => [
                'fnc' => [
                    'post' => 'milestone.note.updateAlias',
                ],
                'parameters' => ['id'],
            ],
            'relate-note' => [
                'fnc' => [
                    'get' => 'milestone.note.detail',
                    'post' => 'milestone.note.add',
                    'put' => 'milestone.note.update',
                    'delete' => 'milestone.note.delete'
                ],
                'parameters' => ['request_id', 'id'],
            ],
            'milestone' => [
                'fnc' => [
                    'get' => 'milestone.milestone.detail',
                    'post' => 'milestone.milestone.add',
                    'put' => 'milestone.milestone.update',
                    'delete' => 'milestone.milestone.delete'
                ],
                'parameters' => ['id'],
                'permission' => [
                    'get' => ['milestone_manager', 'milestone_read'],
                    'post' => ['milestone_manager', 'milestone_create'],
                    'put' => ['milestone_manager', 'milestone_update'],
                    'delete' => ['milestone_manager', 'milestone_delete']
                ],
            ],
            'request-versions' => [
                'fnc' => [
                    'post' => 'milestone.version.list',
                ],
                'parameters' => ['request_id'],
            ],
            'request-version' => [
                'fnc' => [
                    'post' => 'milestone.version.add',
                    'put' => 'milestone.version.update',
                    'delete' => 'milestone.version.delete',
                ],
                'parameters' => ['request_id', 'id'],
            ],
        ];
    }
}
