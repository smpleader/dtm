<?php
/**
 * SPT software - Entity
 * 
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: Just a basic entity
 * 
 */

namespace DTM\note\entities;

use SPT\Storage\DB\Entity;
use SPT\Traits\EntityHasStatus;

class NoteEntity extends Entity
{
    use EntityHasStatus;

    protected $table = '#__note';
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
                'share_user' => [
                    'type' => 'text',
                    'null' => 'YES',
                ],
                'share_user_group' => [
                    'type' => 'text',
                    'null' => 'YES',
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

    public function validate( $data )
    {
        if (!is_array($data))
        {
            $this->error = 'Invalid data format! ';
            return false;
        }

        if (!isset($data['title']) || !$data['title'] || !$data)
        {
            $this->error = 'Title is required! ';
            return false;
        }

        unset($data['readyUpdate']);
        unset($data['readyNew']);
        return $data;
    }

    public function bind($data = [], $returnObject = false)
    {
        $row = [];
        $data = (array) $data;
        $fields = $this->getFields();
        $skips = isset($data['id']) && $data['id'] ? ['created_at', 'created_by', 'locked_at', 'locked_by'] : ['id'];
        foreach ($fields as $key => $field)
        {
            if (!in_array($key, $skips))
            {
                $default = isset($field['default']) ? $field['default'] : '';
                $row[$key] = isset($data[$key]) ? $data[$key] : $default;
            }
        }

        if (isset($data['id']) && $data['id'])
        {
            $row['readyUpdate'] = true;
        }
        else{
            $row['readyNew'] = true;
        }

        return $returnObject ? (object)$row : $row;
    }
}