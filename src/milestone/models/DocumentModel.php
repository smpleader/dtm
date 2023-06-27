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

class DocumentModel extends Base 
{ 
    // Write your code here
    public function remove($id)
    {
        $discussion = $this->DiscussionEntity->list(0, 0, ['document_id = '. $id]);

        $try = $this->DocumentEntity->remove($id);
        if ($try)
        {
            foreach ($discussion as $item)
            {
                $this->DiscussionEntity->remove($item['id']);
            }
        }

        return $try;
    }   

    public function save($data)
    {
        if (!$data || !$data['request_id'])
        {
            return false;
        }

        $find = $this->DocumentEntity->findOne(['request_id' => $data['request_id']]);

        if ($find)
        {
            $try = $this->DocumentEntity->update([
                'description' => $data['description'],
                'modified_by' => $this->user->get('id'),
                'modified_at' => date('Y-m-d H:i:s'),
                'id' => $find['id'],
            ]);
            $document_id = $find['id'];
        }
        else
        {
            $try =  $this->DocumentEntity->add([
                'request_id' => $data['request_id'],
                'description' => $data['description'],
                'created_by' => $this->user->get('id'),
                'created_at' => date('Y-m-d H:i:s'),
                'modified_by' => $this->user->get('id'),
                'modified_at' => date('Y-m-d H:i:s')
            ]);
            $document_id = $try;
        }

        if ($try)
        {
            $try_history = $this->DocumentHistoryEntity->add([
                'document_id' => $document_id,
                'description' => $data['description'],
                'modified_by' => $this->user->get('id'),
                'modified_at' => date('Y-m-d H:i:s')
            ]);
        }

        return $try;
    }

    public function getHistory($request_id)
    {
        if (!$request_id)
        {
            return false;
        }

        $document = $this->DocumentEntity->findOne(['request_id' => $request_id]);
        if (!$document)
        {
            return false;
        }

        $list = $this->DocumentHistoryEntity->list(0 ,0 ,['document_id' => $document['id']], 'id DESC');
        if ($list)
        {
            foreach($list as &$item)
            {
                $user_tmp = $this->UserEntity->findByPK($item['modified_by']);
                if ($user_tmp)
                {
                    $item['modified_by'] = $user_tmp['name'];
                }
            }
        }
        
        return $list;
    }

    public function getComment($request_id)
    {
        if (!$request_id)
        {
            return false;
        }

        $document = $this->DocumentEntity->findOne(['request_id' => $request_id]);
        if (!$document)
        {
            return false;
        }

        $discussion = $this->DiscussionEntity->list(0, 0, ['document_id = '. $document['id']], 'sent_at asc');
        $discussion = $discussion ? $discussion : [];
        foreach ($discussion as &$item)
        {
            $user_tmp = $this->UserEntity->findByPK($item['user_id']);
            $item['user'] = $user_tmp ? $user_tmp['name'] : '';
        }

        $result = $discussion ? $discussion : [];
        return $result;
    }

    public function rollback($id)
    {
        $document = $this->DocumentHistoryEntity->findByPK($id);
        if (!$document)
        {
            return false;
        }
        
        $try = $this->DocumentEntity->update([
            'id' => $document['document_id'],
            'description' => $document['description'],
        ]);

        if ($try)
        {
            $remove_list = $this->DocumentHistoryEntity->list(0, 0, ['id > '. $id, 'document_id = '. $document['document_id']]);
            if ($remove_list)
            {
                foreach($remove_list as $item)
                {
                    $this->DocumentHistoryEntity->remove($item['id']);
                } 
            }
        }
        
        return $try ? $document : false;
    }
}
