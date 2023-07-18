<?php
/**
 * SPT software - Entity
 * 
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: Just a basic entity
 * 
 */

namespace DTM\note2\entities;

use SPT\Storage\DB\Entity;
use SPT\Traits\EntityHasStatus;

class Note2Entity extends Entity
{
    use EntityHasStatus;

    protected $table = '#__note2';
    protected $pk = 'id';

    public function getFields()
    {
        return [
                'id' => [
                    'type' => 'int',
                    'pk' => 1,
                    'option' => 'unsigned',
                    'extra' => 'auto_increment',
                ],
                'public_id' => [
                    'type' => 'varchar', 
                    'limit' => 15,
                ],
                'title' => [
                    'type' => 'varchar',
                    'limit' => 245,
                ], 
                'alias' => [
                    'type' => 'varchar',
                    'limit' => 245,
                ], 
                'data' => [
                    'type' => 'text',
                    'default' => ''
                ],
                'tags' => [
                    'type' => 'text',
                    'default' => '',
                ],
                'created_at' => [
                    'type' => 'datetime',
                    'default' => 'NOW()',
                ],
                'created_by' => [
                    'type' => 'int',
                    'option' => 'unsigned',
                ],
                'locked_at' => [
                    'type' => 'datetime',
                    'default' => 'NOW()',
                ],
                'locked_by' => [
                    'type' => 'int',
                    'option' => 'unsigned',
                ],
                'notice' => [
                    'type' => 'text',
                    'default' => ''
                ],
                'note_ids' => [
                    'type' => 'text',
                ],
                'status' => [
                    'type' => 'int',
                    'default' => 0,
                ],
                'type' => [
                    'type' => 'varchar',
                    'limit' => 45,
                    'default' => 'html',
                ]
        ];
    }
}