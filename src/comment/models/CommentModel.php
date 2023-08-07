<?php
/**
 * SPT software - Model
 * 
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: Just a basic model
 * 
 */

namespace DTM\comment\models;

use SPT\Container\Client as Base;

class CommentModel extends Base
{ 
    use \SPT\Traits\ErrorString;

    public function validate($data)
    {
        if (!$data && !is_array($data))
        {
            $this->error = 'Invalid format data';
            return false;
        }

        if(!$data['comment'])
        {
            $this->error = "Comment can't empty";
            return false;
        }

        if(!$data['object'] || !$data['object_id'])
        {
            $this->error = "Invalid object comment";
            return false;
        }

        return true;
    }

    public function add($data)
    {
        $data = $this->CommentEntity->bind($data, false);
        
        if (!$this->validate($data))
        {
            return false;
        }

        $try = $this->CommentEntity->add([
            'object' => $data['object'],
            'object_id' => $data['object_id'],
            'comment' => $data['comment'],
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->user->get('id'),
        ]);

        if (!$try)
        {
            $this->error = "Can't create comment";
            return false;
        }

        return $try;
    }

    public function update($data)
    {
        $data = $this->CommentEntity->bind($data, false);

        if (!$this->validate($data) || !$data['id'])
        {
            return false;
        }

        $try = $this->CommentEntity->update([
            'object' => $data['object'],
            'object_id' => $data['object_id'],
            'comment' => $data['comment'],
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->user->get('id'),
        ]);

        if (!$try)
        {
            $this->error = "Can't update comment";
            return false;
        }

        return $try;
    }

    public function remove($id)
    {
        if (!$id)
        {
            $this->error = 'Invalid id';
            return false;
        }

        $try = $this->CommentEntity->remove($id);

        if (!$try)
        {
            $this->error = "Can't remove comment";
            return false;
        }

        return $try;
    }

    public function list($start, $limit, $where)
    {
        $list = $this->CommentEntity->list($start, $limit, $where, 'created_at asc');
        $list = $list ? $list : [];
        
        foreach ($list as &$item)
        {
            $user_tmp = $this->UserEntity->findByPK($item['created_by']);
            $item['user'] = $user_tmp ? $user_tmp['name'] : '';
        }

        return $list;
    }
}
