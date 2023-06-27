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

class DocumentHistoryEntity extends Entity
{
    protected $table = '#__document_history';
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
                'document_id' => [
                    'type' => 'int',
                    'option' => 'unsigned',
                ],
                'description' => [
                    'type' => 'text',
                    'null' => 'YES',
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