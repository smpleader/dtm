<?php
namespace DTM\shortcut\entities;

use SPT\Storage\DB\Entity;
use SPT\Traits\EntityHasStatus;

class ShortcutEntity extends Entity
{
    use EntityHasStatus;

    protected $table = '#__shortcuts';
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
                'group' => [
                    'type' => 'varchar',
                    'limit' => 245,
                ],
                'link' => [
                    'type' => 'varchar',
                    'limit' => 245,
                ],
                'created_at' => [
                    'type' => 'datetime',
                    'default' => 'NOW()',
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

        if (!isset($data['name']) || !$data['name'] || !$data)
        {
            $this->error = 'Name is required! ';
            return false;
        }

        if (!isset($data['link']) || !$data['link'] || !$data)
        {
            $this->error = 'Link is required!';
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
        $skips = isset($data['id']) && $data['id'] ? ['created_at', 'created_by', 'modified_at', 'modified_by'] : ['id'];
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