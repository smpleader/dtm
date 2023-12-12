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

    public function add($data)
    {
        $data = $this->TagEntity->bind($data);

        if (!$data || !isset($data['readyNew']) || !$data['readyNew'])
        {
            $this->error = $this->TagEntity->getError();
            return false;
        }
        unset($data['readyNew']);

        $newId =  $this->TagEntity->add($data);

        if (!$newId)
        {
            $this->error = $this->TagEntity->getError();
            return false;
        }

        return $newId;
    }

    public function update($data)
    {
        $data = $this->TagEntity->bind($data);

        if (!$data || !isset($data['readyUpdate']) || !$data['readyUpdate'])
        {
            $this->error = $this->TagEntity->getError();
            return false;
        }
        unset($data['readyUpdate']);

        $try = $this->TagEntity->update($data);
        if (!$try)
        {
            $this->error = $this->TagEntity->getError();
            return false;
        }


        return $try;
    }

    public function search($search, $ignores)
    {
        $where = [];

        if( !empty($search) )
        {
            $search = explode(':', $search);
            if (count($search) > 1)
            {
                $where[] = "(#__tags.name LIKE '%".$search[1]."%' AND parent_tag.name LIKE '%".$search[0]."%')";
            }
            else
            {
                $where[] = "(#__tags.name LIKE '%".$search[0]."%' OR parent_tag.name LIKE '%".$search[0]."%')";
            }
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
                    $item['name'] = $tmp['name']. ':'. $item['name'];
                }
            }
        }

        return $data;
    }

    public function convert($data, $check = true)
    {
        if ($check)
        {
            if (!is_array($data))
            {
                $this->error = 'Invalid data format';
                return false;
            }

            $data = implode('),(', $data);
            $data = $data ? '('. $data .')' : '';
            return $data;
        }

        if (!is_string($data))
        {
            $this->error = 'Invalid data format';
            return false;
        }

        $data = str_replace(['(', ')'], '', $data);
        $data = explode(',', $data);
        return $data;
    }
}
