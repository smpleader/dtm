<?php
/**
 * SPT software - Model
 * 
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: Just a basic model
 * 
 */

namespace DTM\tag\models;

use SPT\Container\Client as Base;

class TagModel extends Base
{ 
    // Write your code here
    use \SPT\Traits\ErrorString;

    public function remove($id)
    {
        if(!$id)
        {
            return false;
        }

        return $this->TagEntity->remove($id);
    }

    public function validate($data)
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

        $findOne = $this->TagEntity->findOne($where);
        if ($findOne)
        {
            $this->error = 'Tag already exists';
            return false;
        }

        return $data;
    }

    public function add($data)
    {
        $try = $this->validate($data);

        if (!$try)
        {
            return false;
        }

        $newId =  $this->TagEntity->add([
            'name' => $data['name'],
            'description' => $data['description'],
            'parent_id' => isset($data['parent_id']) ? $data['parent_id'] : 0 ,
        ]);

        return $newId;
    }

    public function update($data)
    {
        $try = $this->validate($data);

        if (!$try || !$data['id'])
        {
            return false;
        }

        $try = $this->TagEntity->update([
            'name' => $data['name'],
            'description' => $data['description'],
            'parent_id' => isset($data['parent_id']) ? $data['parent_id'] : 0,
            'id' => $data['id'],
        ]);

        return $try;
    }

    public function search($search, $ignores)
    {
        $where = [];

        if( !empty($search) )
        {
            $where[] = "(`name` LIKE '%".$search."%' )";
        }

        if ($ignores && is_array($ignores))
        {
            $where[] = "id NOT IN (". implode(',', $ignores).")";
        }

        $data = $this->TagEntity->list(0,100, $where);
        foreach($data as &$item)
        {
            if ($item['parent_id'])
            {
                $tmp = $this->TagEntity->findByPK($item['parent_id']);
                if ($tmp)
                {
                    $item['name'] = $tmp['name']. ' > '. $item['name'];
                }
            }
        }

        return $data;
    }
}
