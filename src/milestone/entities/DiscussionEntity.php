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

class DiscussionEntity extends Entity
{
    protected $table = '#__discussions';
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
                'user_id' => [
                    'type' => 'int',
                    'option' => 'unsigned',
                ],
                'message' => [
                    'type' => 'text',
                ],
                'sent_at' => [
                    'type' => 'datetime',
                    'null' => 'YES',
                ],
                'modified_at' => [
                    'type' => 'datetime',
                    'null' => 'YES',
                ],
        ];
    }

}