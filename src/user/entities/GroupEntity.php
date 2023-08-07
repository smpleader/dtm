<?php
/**
 * SPT software - PHP Session
 * 
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: PHP Session
 * 
 */

namespace DTM\user\entities;

use SPT\Storage\DB\Entity;

class GroupEntity extends Entity
{ 
    protected $affix = 'Group';
    protected $table = '#__user_groups';
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
                    'limit' => 50,
                ],
                'description' => [
                    'type' => 'text',
                ],
                'access' => [
                    'type' => 'text',
                ],
                'status' => [
                    'type' => 'int',
                ],
                'created_at' => [
                    'type' => 'datetime',
                    'null' => 'YES',
                ],
                'created_by' => [
                    'type' => 'tinyint',
                    'option' => 'unsigned',
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

    public function toggleActive( $id , $action)
    {
        $item = $this->findByPK($id);
        $status = $action == 'active' ? 1 : 0;
        return $this->db->table( $this->table )->update([
            'status' => $status,
        ], ['id' => $id ]);
    }

    public function validate( $data )
    {
        if (!$data || !is_array($data))
        {
            $this->error = "Data invalid format";
            return false;
        }

        if(!empty($data['name'])) 
        {
            $find = $this->findOne(['name' => $data['name']]);
            if ($find && ((isset($data['id']) && $find['id'] != $data['id']) || !isset($data['id']) || !$data['id']))
            {
                $this->error = "Group Name already exists";
                return false;
            }
        } 
        else 
        {
            $this->error = "Group name can't empty";
            return false;
        }

        return $data;
    }

    public function bind($data = [], $returnObject = false)
    {
        $row = [];
        $data = (array) $data;
        $fields = $this->getFields();
        $skips = isset($data['id']) && $data['id'] ? ['created_at', 'created_by'] : ['id'];
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
