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

class DiscussionModel extends Base 
{ 
    // Write your code here
    public function validate($data)
    {
        if (!$data || !isset($data['request_id']) || !$data['request_id'])
        {
            return false;
        }
        if (!$data['message'])
        {
            $this->set('message', 'Message discussion can\'t empty!');
            return false;
        }

        $document = $this->DocumentEntity->findOne(['request_id' => $data['request_id']]);
        if (!$document)
        {
            $newId =  $this->DocumentEntity->add([
                'request_id' => $data['request_id'],
                'description' => '',
                'created_by' => $this->user->get('id'),
                'created_at' => date('Y-m-d H:i:s'),
                'modified_by' => $this->user->get('id'),
                'modified_at' => date('Y-m-d H:i:s')
            ]);

            if(!$newId)
            {
                $this->set('message', 'Comment Fail');
                return false;
            }
            
            $data['document_id'] = $newId;
        }
        else
        {
            $data['document_id'] = $document['id'];
        }

        return $data;
    } 
    
    public function add($data)
    {
        if (!$data || !isset($data['document_id']) || !$data['document_id'])
        {
            return false;
        }

        $newId = $this->DiscussionEntity->add([
            'user_id' => $this->user->get('id'),
            'document_id' => $data['document_id'],
            'message' => $data['message'],
            'sent_at' => date('Y-m-d H:i:s'),
            'modified_at' => date('Y-m-d H:i:s'),
        ]);
        
        return $newId;
    }
}
