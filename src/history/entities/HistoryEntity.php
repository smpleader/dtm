<?php
/**
 * SPT software - Entity
 * 
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: Just a basic entity
 * 
 */

namespace DTM\history\entities;

use SPT\Storage\DB\Entity;

class HistoryEntity extends Entity
{
    protected $table = '#__histories';
    protected $pk = 'id';

    public function getFields()
    {
        return [
                'id' => [
                    'type' => 'bigint',
                    'pk' => 1,
                    'option' => 'unsigned',
                    'extra' => 'auto_increment',
                ],
                'object_id' => [
                    'type' => 'int',
                ],
                'object' => [
                    'type' => 'varchar',
                    'limit' => 255,
                ],
                'data' => [
                    'type' => 'text',
                ],
                'created_at' => [
                    'type' => 'datetime',
                    'null' => 'YES',
                ],
                'created_by' => [
                    'type' => 'int',
                    'option' => 'unsigned',
                ],
        ];
    }

    public function validate( $data )
    {
        if (!$data && !is_array($data))
        {
            $this->error = 'Invalid format data';
            return false;
        }

        if(!$data['object'] || !$data['object_id'])
        {
            $this->error = "Invalid object history";
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