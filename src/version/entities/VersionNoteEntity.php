<?php
/**
 * SPT software - Entity
 * 
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: Just a basic entity
 * 
 */

namespace DTM\version\entities;

use SPT\Storage\DB\Entity;

class VersionNoteEntity extends Entity
{
    protected $table = '#__version_notes'; //table name
    protected $pk = 'id'; //primary key

    public function getFields()
    {
        return [
            'id' => [
                'type' => 'int',
                'pk' => 1,
                'option' => 'unsigned',
                'extra' => 'auto_increment',
            ],
            'version_id' => [
                'type' => 'int',
                'option' => 'unsigned',
            ],
            'request_id' => [
                'type' => 'int',
                'option' => 'unsigned',
                'null' => 'YES',
            ],
            'log' => [
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