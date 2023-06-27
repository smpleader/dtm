<?php
/**
 * SPT software - Entity
 * 
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: Just a basic entity
 * 
 */

namespace DTM\setting\entities;

use SPT\Storage\DB\Entity;

class OptionEntity extends Entity
{
    protected $table = '#__options'; //table name
    protected $pk = 'id'; //primary key

    public function getFields()
    {
        return [
            // write your code here
            'id' => [
                'type' => 'int',
                'pk' => 1,
                'option' => 'unsigned',
                'extra' => 'auto_increment',
            ],
            'type' => [
                'type' => 'varchar',
                'limit' => 100,
            ],
            'data' => [
                'type' => 'text',
                'null' => 'YES',
            ],
        ];
    }
}