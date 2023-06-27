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

class RelateNoteEntity extends Entity
{
    protected $table = '#__relate_notes';
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
                'alias' => [
                    'type' => 'varchar',
                    'limit' => 255,
                    'null' => 'YES',
                ],
                'note_id' => [
                    'type' => 'int',
                    'option' => 'unsigned',
                    'null' => 'YES'
                ],
                'description' => [
                    'type' => 'text',
                    'null' => 'YES'
                ],
        ];
    }

}