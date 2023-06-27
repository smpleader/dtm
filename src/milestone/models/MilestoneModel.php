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

class MilestoneModel extends Base 
{ 
    // Write your code here
    public function remove($id)
    {
        $requests = $this->RequestEntity->list(0, 0, ['milestone_id = '. $id]);
        $try = $this->MilestoneEntity->remove($id);
        if ($try)
        {
            foreach ($requests as $request)
            {
                $this->RequestModel->remove($request['id']);
            }
        }
        return $try;
    }   

    public function validate($data)
    {
        if (!$data || !is_array($data))
        {
            return false;
        }

        if (!$data['title']) 
        {
            $this->session->set('flashMsg', 'Error: Title can\'t empty! ');
            return false;
        }

        $where = ['title = "' . $title . '"'];
        if (isset($data['id']) && $data['id'])
        {
            $where[] = 'id <> '. $data['id'];
        }
        $findOne = $this->MilestoneEntity->findOne($where);
        if ($findOne) {
            $this->session->set('flashMsg', 'Error: Title is already in use! ');
            return false;
        }

        if ($data['start_date'] == '')
            $data['start_date'] = NULL;
        if ($data['end_date'] == '')
            $data['end_date'] = NULL;

        return true;
    }

    public function add($data)
    {
        if (!$data || !is_array($data))
        {
            return false;
        }

        $newId =  $this->MilestoneEntity->add([
            'title' => $data['title'],
            'description' => $data['description'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'status' => $data['status'],
            'created_by' => $this->user->get('id'),
            'created_at' => date('Y-m-d H:i:s'),
            'modified_by' => $this->user->get('id'),
            'modified_at' => date('Y-m-d H:i:s')
        ]);

        return $newId;
    }

    public function update($data)
    {
        if (!$data || !is_array($data) || !$data['id'])
        {
            return false;
        }

        $try = $this->MilestoneEntity->update([
            'title' => $data['title'],
            'description' => $data['description'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'status' => $data['status'],
            'modified_by' => $this->user->get('id'),
            'modified_at' => date('Y-m-d H:i:s'),
            'id' => $data['id'],
        ]);

        return $try;
    }
}
