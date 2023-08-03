<?php
/**
 * SPT software - Entity
 * 
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: Just a basic entity
 * 
 */

namespace DTM\comment\entities;

use SPT\Storage\DB\Entity;

class CommentEntity extends Entity
{
    protected $table = '#__comments';
    protected $pk = 'id';

    public function getFields()
    {
        return [
                'id' => [
                    'type' => 'bigint',
                    'pk' => 1,
                    'option' => 'unsigned',
                    'extra' => 'auto_increment',
                ],
                'object_id' => [
                    'type' => 'int',
                ],
                'object' => [
                    'type' => 'varchar',
                    'limit' => 255,
                ],
                'comment' => [
                    'type' => 'text',
                ],
                'created_at' => [
                    'type' => 'datetime',
                    'null' => 'YES',
                ],
                'created_by' => [
                    'type' => 'int',
                    'option' => 'unsigned',
                ],
        ];
    }
}