<?php
/**
 * SPT software - Model
 * 
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: Just a basic model
 * 
 */

namespace DTM\milestone\models;

use SPT\Container\Client as Base;

class RelateNoteModel extends Base 
{ 
    // Write your code here
    public function removeByNote($id)
    {
        if (!$id)
        {
            return false;
        }

        $finds = $this->RelateNoteEntity->list(0, 0, ['note_id' => $id]);
        foreach($finds as $item)
        {
            $try = $this->RelateNoteEntity->remove($item['id']);
            if (!$try) return false;
        }

        return true;
    }   

    public function  remove($id)
    {
        if (!$id) return false;
        $try = $this->RelateNoteEntity->remove($id);
        
        return $try;
    }

    public function addNote($notes, $request_id)
    {
        if (!$notes || !$request_id)
        {
            return false;
        }

        foreach($notes as $note_id)
        {
            $find = $this->RelateNoteEntity->findOne(['request_id' => $request_id, 'note_id' => $note_id]);
            if ($find) continue;
            
            $newId =  $this->RelateNoteEntity->add([
                'request_id' => $request_id,
                'title' => '',
                'note_id' => $note_id,
                'description' => '',
            ]);

            if (!$newId) return false;
        }

        return true;
    }

    public function getNotes($request_id, $search)
    {
        $list = $this->RelateNoteEntity->list( 0, 0, ['request_id' => $request_id], 0);
        $result = [];
        foreach ($list as &$item)
        {
            $note_tmp = $this->NoteEntity->findByPK($item['note_id']);
            if ($note_tmp)
            {
                $item['title'] = $note_tmp['title'];
                $item['description'] = strip_tags((string) $note_tmp['description']) ;
                $item['tags'] = $note_tmp['tags'] ;
                if (strlen($item['description']) > 100)
                {
                    $item['description'] = $this->RequestModel->excerpt($item['description']);
                }

                if (in_array($note_tmp['type'], ['presenter', 'sheetjs']))
                {
                    $item['description'] = '';
                }
            }

            if (!empty($item['tags'])){
                $t1 = $where = [];
                $where[] = "(`id` IN (".$item['tags'].") )";
                $t2 = $this->TagEntity->list(0, 1000, $where,'','`name`');

                foreach ($t2 as $i) $t1[] = $i['name'];

                $item['tags'] = implode(', ', $t1);
            }

            if ($search)
            {
                if (strpos($item['title'], $search) === false && strpos($item['description'], $search) === false && strpos($item['tags'], $search) === false )
                {
                    continue;
                }
            }
            $result[] = $item;
        }

        return $result;
    }

    public function updateAlias($data)
    {
        if (!$data || !isset($data['id']) || !$data['id'])
        {
            return false;
        }

        $try = $this->RelateNoteEntity->update($data);
        
        return $try;
    }
}
