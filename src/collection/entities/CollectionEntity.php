<?php
namespace DTM\collection\entities;

use SPT\Storage\DB\Entity;
use SPT\Traits\EntityHasStatus;

class CollectionEntity extends Entity
{
    use EntityHasStatus;

    protected $table = '#__collections';
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
                'user_id' => [
                    'type' => 'int', 
                    'option' => 'unsigned',
                ],
                'name' => [
                    'type' => 'varchar',
                    'limit' => 245,
                ], 
                'filter_link' => [
                    'type' => 'varchar',
                    'limit' => 245,
                ],
                'select_object' => [
                    'type' => 'varchar',
                    'limit' => 245,
                ],
                'start_date' => [
                    'type' => 'datetime',
                    'default' => 'NOW()',
                    'null' => 'YES'
                ],
                'end_date' => [
                    'type' => 'datetime',
                    'default' => 'NOW()',
                    'null' => 'YES'
                ],
                'tags' => [
                    'type' => 'text',
                ],
                'creator' => [
                    'type' => 'text',
                ],
                'ignore_creator' => [
                    'type' => 'text',
                ],
                'assignment' => [
                    'type' => 'text',
                ],
                'created_at' => [
                    'type' => 'datetime',
                    'default' => 'NOW()',
                ],
                'shortcut_id' => [
                    'type' => 'int', 
                    'option' => 'unsigned',
                ],
                'created_by' => [
                    'type' => 'int',
                    'option' => 'unsigned',
                ],
                'modified_at' => [
                    'type' => 'datetime',
                    'default' => 'NOW()',
                ],
                'modified_by' => [
                    'type' => 'int',
                    'option' => 'unsigned',
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

        if (!isset($data['name']) || !$data['name'])
        {
            $this->error = 'Name is required! ';
            return false;
        }

        $where = ['name Like "'. $data['name'] .'"', 'user_id LIKE '. $data['user_id']];
        if(isset($data['id']))
        {
            $where[] = 'id <> '. $data['id'];
        }
        
        $findOne = $this->findOne($where);

        if ($findOne)
        {
            $this->error = 'Name already used!';
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
        $skips = isset($data['id']) && $data['id'] ? ['created_at', 'shortcut_id', 'created_by', 'modified_at', 'modified_by'] : ['id'];
        foreach ($fields as $key => $field)
        {
            if (!in_array($key, $skips))
            {
                $default = isset($field['default']) ? $field['default'] : '';
                $row[$key] = isset($data[$key]) ? $data[$key] : $default;
            }
            if ($key == 'start_date' || $key == 'end_date')
            {
                $row[$key] =  $row[$key] ?  $row[$key]  : null;
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