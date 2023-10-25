<?php
/**
 * SPT software - Entity
 * 
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: Just a basic entity
 * 
 */

namespace DTM\media\entities;

use SPT\Storage\DB\Entity;

class MediaEntity extends Entity
{
    protected $table = '#__media';
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
                'name' => [
                    'type' => 'varchar',
                    'limit' => 255,
                ],
                'path' => [
                    'type' => 'varchar',
                    'limit' => 255,
                ],
                'note' => [
                    'type' => 'text',
                    'null' => 'YES',
                ],
                'type' => [
                    'type' => 'text',
                    'null' => 'YES',
                ],
                'created_at' => [
                    'type' => 'datetime',
                ],
                'created_by' => [
                    'type' => 'int',
                ],
                'modified_at' => [
                    'type' => 'datetime',
                ],
                'modified_by' => [
                    'type' => 'int',
                ],
        ];
    }

    public function validate( $data )
    {
        if (!$data || !is_array($data))
        {
            return false;
        }

        if (!$data['name'])
        {
            $this->error = 'Name can\'t empty! ';
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
        $skips = isset($data['id']) && $data['id'] ? [] : ['id'];
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