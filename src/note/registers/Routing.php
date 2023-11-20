<?php

namespace DTM\note\registers;

use SPT\Application\IApp;

class Routing
{
    public static function registerEndpoints()
    {
        return [
            'notes' => [
                'fnc' => [
                    'get' => 'note.note.list',
                    'post' => 'note.note.list',
                    'put' => 'note.note.update',
                    'delete' => 'note.note.delete',
                ],
                'permission' => [
                    'get' => ['note_manager'],
                ],
            ],
            'notes/trash' => [
                'fnc' => [
                    'get' => 'note.note.trash',
                    'post' => 'note.note.trash',
                    'put' => 'note.note.restore',
                    'delete' => 'note.note.hardDelete',
                ],
                'permission' => [
                    'get' => ['note_manager'],
                ],
            ],
            'my-notes' => [
                'fnc' => [
                    'get' => 'note.note.list',
                    'post' => 'note.note.list',
                    'put' => 'note.note.update',
                    'delete' => 'note.note.delete',
                ],
                'filter' => 'my-note',
            ],
            'my-notes/trash' => [
                'fnc' => [
                    'get' => 'note.note.trash',
                    'post' => 'note.note.trash',
                ],
                'filter' => 'my-note',
            ],
            'share-notes' => [
                'fnc' => [
                    'get' => 'note.note.list',
                    'post' => 'note.note.list',
                    'put' => 'note.note.update',
                    'delete' => 'note.note.delete',
                ],
                'filter' => 'share-note',
            ],
            'note/detail' => [
                'fnc' => [
                    'get' => 'note.note.detail',
                ],
                'parameters' => ['id'],
                'loadChildPlugin' => true,
                'permissionGroup' => true,
            ],
            'note/edit' => [
                'fnc' => [
                    'get' => 'note.note.form',
                    'put' => 'note.note.update',
                ],
                'parameters' => ['id'],
                'allowShare' => false,
                'loadChildPlugin' => true,
            ],
            'note/preview' => [
                'fnc' => [
                    'get' => 'note.note.preview',
                ],
                'parameters' => ['id'],
                'allowShare' => false,
                'loadChildPlugin' => true,
                'permissionGroup' => true,
            ],
            'note/search' => [
                'fnc' => [
                    'get' => 'note.note.search',
                ]
            ],
            'new-note' => [
                'fnc' => [
                    'get' => 'note.note.newform',
                    'post' => 'note.note.add',
                ],
                'parameters' => ['type'],
                'loadChildPlugin' => true,
            ],
        ];
    }
}
