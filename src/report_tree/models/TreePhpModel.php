<?php
/**
 * SPT software - Model
 * 
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: Just a basic model
 * 
 */

namespace DTM\report_tree\models;

use SPT\Container\Client as Base;
use SPT\Traits\ErrorString;

class TreePhpModel extends Base
{ 
    use ErrorString; 

    // Write your code here
    public function getTree($id)
    {
        $list = $this->TreeStructureEntity->list(0, 0, ['diagram_id ='.$id], 'tree_left asc');
        $removes = [];
        foreach($list as &$item)
        {
            if (in_array($item['id'], $removes))
            {
                $this->TreeStructureEntity->remove($item['id']);
                continue;
            }

            $note = $this->Note2Entity->findByPK($item['note_id']);
            if (!$note)
            {
                $removes[] = $item['id'];
                $this->TreeStructureEntity->remove($item['id']);
            }
            else
            {
                $item['title'] = $note['title'];
            }
        }

        if ($removes)
        {
            $this->TreeStructureEntity->rebuild($id);
            $list = $this->getTree($id);
        }
        
        return $list;
    }

    public function findNotes($config)
    {
        $notes = [];
        foreach($config as $key => &$item)
        {
            if ($item['id'])
            {
                $notes[] = $item['id'];
            }

            if (isset($item['children']) && $item['children'])
            {
                $notes = array_merge($notes, $this->findNotes($item['children']) ) ;
            }
        }

        return $notes;
    }

    public function remove($id)
    {
        // remove in tree structure
        if (!$id)
        {
            return false;
        }

        $list = $this->TreeStructureEntity->list(0, 0, ['diagram_id = '. $id]);
        
        foreach($list as $item)
        {
            $this->TreeStructureEntity->remove($item['id']);
        }

        $try = $this->ReportEntity->remove($id);
        return $try;
    }

    public function validate($data)
    {
        if (!$data || !is_array($data))
        {
            $this->error = 'Invalid data format';
            return false;
        }

        if (!$data['title'])
        {
            $this->error = "title can't empry";
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

        $newId =  $this->ReportEntity->add([
            'title' => $data['title'],
            'status' => 1,
            'type' => 'tree',
            'created_by' => $this->user->get('id'),
            'created_at' => date('Y-m-d H:i:s'),
            'modified_by' => $this->user->get('id'),
            'modified_at' => date('Y-m-d H:i:s')
        ]);

        if (!$newId)
        {
            $this->error = "Can't create report";
        }

        if ($newId && $data['structure'])
        {
            $structure = json_decode($data['structure'], true);
            foreach($structure as $item)
            {
                $this->TreeStructureEntity->add([
                    'diagram_id' => $newId,
                    'note_id' => $item['id'],
                    'tree_position' => $item['id'] ? $item['position'] : 0,
                    'tree_level' => $item['id'] ? $item['level'] : 0,
                    'parent_id' => $item['id'] ? $item['parent'] : 0,
                    'tree_left' => 0,
                    'tree_right' => 0,
                ]);
            }

            $try = $this->TreeStructureEntity->rebuild($newId);
        }

        return $newId;
    }

    public function update($data)
    {
        $try = $this->validate($data);
        if (!$try || !$data['id'])
        {
            return false;
        }

        $try = $this->ReportEntity->update([
            'title' => $data['title'],
            'modified_by' => $this->user->get('id'),
            'modified_at' => date('Y-m-d H:i:s'),
            'id' => $data['id'],
        ]);

        if ($try && $data['structure'])
        {
            $structure = json_decode($data['structure'], true);
            $removes = json_decode($data['removes'], true);
            foreach($removes as $item)
            {
                $find = $this->TreeStructureEntity->findOne(['note_id = '. $item, 'diagram_id = '. $data['id'] ]);
                if ($find)
                {
                    $this->TreeStructureEntity->remove($find['id']);
                }
            }

            foreach($structure as $item)
            {
                $find = $this->TreeStructureEntity->findOne(['note_id = '. $item['id'], 'diagram_id = '. $data['id'] ]);
                if ($find)
                {
                    $try = $this->TreeStructureEntity->update([
                        'id' => $find['id'],
                        'tree_position' => $item['id'] ? $item['position'] : 0,
                        'tree_level' => $item['id'] ? $item['level'] : 0,
                        'parent_id' => $item['id'] ? $item['parent'] : 0,
                    ]);
                }else{
                    $try = $this->TreeStructureEntity->add([
                        'diagram_id' => $data['id'],
                        'note_id' => $item['id'],
                        'tree_position' => $item['id'] ? $item['position'] : 0,
                        'tree_level' => $item['id'] ? $item['level'] : 0,
                        'parent_id' => $item['id'] ? $item['parent'] : 0,
                        'tree_left' => 0,
                        'tree_right' => 0,
                    ]);
                }
            }
            $try = $this->TreeStructureEntity->rebuild($data['id']);
        }

        return $try;
    }

    public function getDetail($id)
    {
        if (!$id)
        {
            $this->error = 'Invalid id';
            return false;
        }

        $find = $this->ReportEntity->findByPK($id);
        if (!$find)
        {
            $this->error = 'Invalid report';
            return false;
        }

        $list_tree = $this->getTree($id);
        $find['list_tree'] = $list_tree;
        $ignore = [];

        foreach($list_tree as $item)
        {
            $ignore[] = $item['note_id'];
        }

        $find['ignore'] = $ignore;

        return $find;
    }
}
