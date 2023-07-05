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
    public function remove($id)
    {
        $where = [
            "(`tags` = '" . $id . "'" .
            " OR `tags` LIKE '%" . ',' . $id . "'" .
            " OR `tags` LIKE '" . $id . ',' . "%'" .
            " OR `tags` LIKE '%" . ',' . $id . ',' . "%' )"
        ];

        //find note
        $list_note = $this->NoteEntity->list(0, 0, $where);
        foreach($list_note as $note)
        {
            $tags = $note['tags'] ? explode(',', $note['tags']) : [];
            $key = array_search($id, $tags);
            unset($tags[$key]);
            $this->NoteEntity->update([
                'tags' => implode(',', $tags),
                'id' => $note['id'],
            ]);
        }

        //find Request
        $list_request = $this->RequestEntity->list(0, 0, $where);
        foreach($list_request as $request)
        {
            $tags = $request['tags'] ? explode(',', $request['tags']) : [];
            $key = array_search($id, $tags);
            unset($tags[$key]);
            $this->RequestEntity->update([
                'tags' => implode(',', $tags),
                'id' => $request['id'],
            ]);
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
            $this->session->set('flashMsg', 'Error: Name can\'t empty! ');
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
            $this->session->set('flashMsg', 'Error: Create Failed! Tag already exists');
            return false;
        }

        return $data;
    }

    public function add($data)
    {
        if (!$data || !is_array($data))
        {
            return false;
        }

        $newId =  $this->TagEntity->add([
            'name' => $data['name'],
            'description' => $data['description'],
            'parent_id' => $data['parent_id'],
        ]);

        return $newId;
    }

    public function update($data)
    {
        if (!$data || !is_array($data) || !$data['id'])
        {
            return false;
        }

        $try = $this->TagEntity->update([
            'name' => $data['name'],
            'description' => $data['description'],
            'parent_id' => $data['parent_id'],
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
