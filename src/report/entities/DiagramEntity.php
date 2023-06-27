<?php
/**
 * SPT software - Entity
 * 
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: Just a basic entity
 * 
 */

namespace DTM\report\entities;

use SPT\Storage\DB\Entity;

class DiagramEntity extends Entity
{
    protected $table = '#__diagrams';
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
            'title' => [
                'type' => 'varchar',
                'limit' => 255,
            ],
            'status' => [
                'type' => 'tinyint',
            ],
            'report_type' => [
                'type' => 'varchar',
                'limit' => 255,
            ],
            'config' => [
                'type' => 'text',
                'null' => 'YES',
            ],
            'assignment' => [
                'type' => 'text',
                'null' => 'YES',
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