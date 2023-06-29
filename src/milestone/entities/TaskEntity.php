<?php
/**
 * SPT software - Entity
 * 
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: Just a basic entity
 * 
 */

namespace DTM\milestone\entities;

use SPT\Storage\DB\Entity;

class TaskEntity extends Entity
{
    protected $table = '#__tasks';
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
                'request_id' => [
                    'type' => 'int',
                    'option' => 'unsigned',
                ],
                'title' => [
                    'type' => 'varchar',
                    'limit' => 255,
                    'null' => 'YES',
                ],
                'status' => [
                    'type' => 'tinyint',
                    'option' => 'unsigned',
                    'null' => 'YES'
                ],
                'url' => [
                    'type' => 'text',
                    'null' => 'YES'
                ],
                'created_at' => [
                    'type' => 'datetime',
                    'null' => 'YES',
                ],
                'created_by' => [
                    'type' => 'int',
                    'option' => 'unsigned',
                ],
                'modified_at' => [
                    'type' => 'datetime',
                    'null' => 'YES',
                ],
                'modified_by' => [
                    'type' => 'int',
                    'option' => 'unsigned',
                ],
        ];
    }

}