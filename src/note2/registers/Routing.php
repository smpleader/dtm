<?php

namespace DTM\note2\registers;

use SPT\Application\IApp;

class Routing
{
    public static function registerEndpoints()
    {
        return [
            'note2/types' => [
                'fnc' => [
                    'get' => 'note2.ajax.types',
                ]
            ],
            'note2' => [
                'fnc' => [
                    'get' => 'note2.note.list',
                    'post' => 'note2.note.list',
                    'put' => 'note2.note.update',
                    'delete' => 'note2.note.delete',
                ]
            ],
            'note2/detail' => [
                'fnc' => [
                    'get' => 'note2.note.detail',
                    'put' => 'note2.note.update',
                ],
                'parameters' => ['id'],
                'loadChildPlugin' => true,
                'permission' => [
                    'get' => ['note_manager', 'note_read'],
                ],
            ],
            'note2/search' => [
                'fnc' => [
                    'get' => 'note2.note.search',
                ]
            ],
            'new-note2' => [
                'fnc' => [
                    'get' => 'note2.note.newform',
                    'post' => 'note2.note.add',
                    //'put' => 'note2.note.update',
                    //'delete' => 'note2.note.delete'
                ],
                'parameters' => ['type'],
                'loadChildPlugin' => true,
                'permission' => [
                    'get' => ['note_manager', 'note_read'],
                    'post' => ['note_manager', 'note_create'],
                    'put' => ['note_manager', 'note_update'],
                    'delete' => ['note_manager', 'note_delete']
                ],
            ],
            /*'notes' => [
                'fnc' => [
                    'get' => 'note.note.list',
                    'post' => 'note.note.list',
                    'put' => 'note.note.update',
                    'delete' => 'note.note.delete'
                ],
                'permission' => [
                    'get' => ['note_manager', 'note_read'],
                    'post' => ['note_manager', 'note_read'],
                    'put' => ['note_manager', 'note_update'],
                    'delete' => ['note_manager', 'note_delete']
                ],
            ]
            'note/request' => [
                'fnc' => [
                    'get' => 'note.note.request',
                ],
                'parameters' => ['id'],
            ],
            'attachment' => [
                'fnc' => [
                    'delete' => 'note.attachment.delete'
                ],
                'parameters' => ['id'],
            ],
            'download/attachment' => [
                'fnc' => [
                    'delete' => 'note.attachment.download'
                ],
                'parameters' => ['id'],
            ],
            'note/version' => [
                'fnc' => [
                    'get' => 'note.version.detail',
                    'post' => 'note.version.rollback',
                    'delete' => 'note.version.delete'
                ],
                'parameters' => ['id'],
            ],
            'tag' => [
                'fnc' => [
                    'get' => 'note.tag.list',
                    'post' => 'note.tag.add',
                ]
            ],
            'setting-connections'=>[
                'fnc' => [
                    'get' => 'note.setting.connections',
                    'post' => 'note.setting.connectionsSave',
                ],
            ],*/
        ];
    }
}
