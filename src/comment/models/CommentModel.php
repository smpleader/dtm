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

    public function add($data)
    {
        $data = $this->CommentEntity->bind($data);

        if (!$data || !isset($data['readyNew']) || !$data['readyNew'])
        {
            $this->error = $this->CommentEntity->getError();
            return false;
        }
        unset($data['readyNew']);

        $try = $this->CommentEntity->add($data);

        if (!$try)
        {
            $this->error = $this->CommentEntity->getError();
            return false;
        }

        return $try;
    }

    public function update($data)
    {
        $data = $this->CommentEntity->bind($data);

        if (!$data || !isset($data['readyUpdate']) || !$data['readyUpdate'])
        {
            $this->error = $this->CommentEntity->getError();
            return false;
        }
        unset($data['readyUpdate']);

        $try = $this->CommentEntity->update($data);

        if (!$try)
        {
            $this->error = $this->CommentEntity->getError();
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
