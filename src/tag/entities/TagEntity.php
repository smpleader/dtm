<?php
/**
 * SPT software - Entity
 * 
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: Just a basic entity
 * 
 */

namespace DTM\tag\entities;

use SPT\Storage\DB\Entity;

class TagEntity extends Entity
{
    protected $table = '#__tags';
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
                'parent_id' => [
                    'type' => 'int',
                    'null' => 'YES',
                ],
                'description' => [
                    'type' => 'text',
                    'null' => 'YES',
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

        $where = ['name' => $data['name']];
        if (isset($data['id']) && $data['id'])
        {
            $where[] = 'id <> '. $data['id'];
        }

        $findOne = $this->findOne($where);
        if ($findOne)
        {
            $this->error = 'Tag already exists';
            return false;
        }

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