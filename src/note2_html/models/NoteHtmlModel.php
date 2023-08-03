<?php
/**
 * SPT software - Model
 * 
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: Just a basic model
 * 
 */

namespace DTM\note2_html\models;

use SPT\Container\Client as Base;

class NoteHtmlModel extends Base
{ 
    // Write your code here
    use \SPT\Traits\ErrorString;

    public function replaceContent($content, $encode = true)
    {
        $replace = $encode ? '_sdm_app_domain_' : $this->router->url();
        $search = $encode ? $this->router->url() : '_sdm_app_domain_';
        
        $content = str_replace($search, $replace, $content);

        return $content;
    }

    public function validate($data)
    {
        if (!is_array($data))
        {
            $this->error = 'Error: Invalid data format! ';
            return false;
        }

        if (!isset($data['title']) || !$data['title'] || !$data)
        {
            $this->error = 'Error: Title is required! ';
            return false;
        }

        $where = ['title = "'. $data['title']. '"'];
        if (isset($data['id']) && $data['id'])
        {
            $where[] = 'id <> '. $data['id'];
        }

        $find = $this->Note2Entity->findOne($where);
        if ($find)
        {
            $this->error = 'Error: Title already used! ';
            return false;
        }

        return true;
    }

    public function add($data)
    {
        $try = $this->validate($data);
        if (!$try)
        {
            return false;
        }

        $data['data'] = $this->replaceContent($data['data']);
        $data['tags'] = isset($data['tags']) ? $this->TagModel->convert($data['tags']) : '';

        $newId =  $this->Note2Entity->add([
            'title' => $data['title'],
            'public_id' => '',
            'alias' => '',
            'data' => $data['data'],
            'tags' => $data['tags'],
            'type' => 'html',
            'note_ids' => '',
            'notice' => isset($data['notice']) ? $data['notice'] : '',
            'status' => isset($data['status']) ? $data['status'] : 0,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->user->get('id'),
            'locked_at' => date('Y-m-d H:i:s'),
            'locked_by' => $this->user->get('id'),
        ]);

        return $newId;
    }

    public function update($data)
    {
        $try = $this->validate($data);

        if (!$try || !$data['id'])
        {
            $this->error = 'Invalid note!';
            return false;
        }

        $data['data'] = $this->replaceContent($data['data']);
        $data['tags'] = isset($data['tags']) ? $this->TagModel->convert($data['tags']) : '';

        $try =  $this->Note2Entity->update([
            'title' => $data['title'],
            'data' => $data['data'],
            'tags' => $data['tags'],
            'notice' => isset($data['notice']) ? $data['notice'] : '',
            'status' => isset($data['status']) ? $data['status'] : 0,
            'id' => $data['id'],
        ]);


        return $try;
    }

    public function remove($id)
    {
        if (!$id)
        {
            $this->error = 'Invalid note!';
            return false;
        }

        $try = $this->Note2Entity->remove($id);
        return $try;
    }

    public function getDetail($id)
    {
        if (!$id)
        {
            $find = $this->Note2Entity->findOne(['status' => '-1', 'created_by' => $this->user->get('id'), 'type' => 'html']);
            if (!$find)
            {
                $find = [
                    'title' => '',
                    'public_id' => '',
                    'alias' => '',
                    'data' => '',
                    'tags' => '',
                    'type' => 'html',
                    'note_ids' => '',
                    'status' => -1,
                    'notice' => '',
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => $this->user->get('id'),
                    'locked_at' => date('Y-m-d H:i:s'),
                    'locked_by' => $this->user->get('id'),
                ];
                
                $try = $this->Note2Entity->add($find);

                if (!$try)
                {
                    $this->error = 'Can`t create default note';
                    return false;
                }

                $find['id'] = $try;
            }

            return $find;
        }

        $note = $this->Note2Entity->findByPK($id);
        if (!$note)
        {
            return [];
        }

        return $note;
    }

    public function rollback($id)
    {
        $history = $this->HistoryModel->detail($id);
        if (!$history)
        {
            return false;
        }
        
        $find_note = $this->Note2Entity->findOne(['id' => $history['object_id']]);
        if (!$find_note)
        {
            return false;
        }

        $try = $this->Note2Entity->update([
            'id' => $find_note['id'],
            'data' => $history['data'],
        ]);

        if ($try)
        {
            $remove_list = $this->HistoryEntity->list(0, 0, ['id > '. $id, 'object_id = '. $history['object_id'], 'object' => 'note']);
            if ($remove_list)
            {
                foreach($remove_list as $item)
                {
                    $this->HistoryEntity->remove($item['id']);
                } 
            }
        }
        
        return $try ? $find_note['id'] : false;
    }
}
