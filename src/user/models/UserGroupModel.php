<?php
/**
 * SPT software - Model
 * 
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: Just a basic model
 * 
 */

namespace DTM\user\models;

use SPT\Container\Client as Base;
use SPT\Traits\ErrorString;

class UserGroupModel extends Base
{
    use ErrorString; 

    public function removeByGroup($group_id)
    {
        $user_group = $this->UserGroupEntity->list(0, 0, ['group_id' => $group_id]);
        $try = true;
        foreach($user_group as $value)
        {
            $try = $this->UserGroupEntity->remove($value['id']);
        }

        return $try;
    }

    public function removeByUser($user_id)
    {
        $try = true;
        $user_map = $this->UserGroupEntity->list(0, 0, ['user_id' => $user_id]);
        foreach($user_map as $value)
        {
            $try = $this->UserGroupEntity->remove($value['id']);
        }

        return $try;
    }

    public function updateUserMap($id, $groups_update)
    {
        if (!$id)
        {
            return false;
        }

        $groups = $this->UserEntity->getGroups($id);
        foreach($groups as $group)
        {
            if (!in_array($group['group_id'], $groups_update))
            {
                $user_map = $this->UserGroupEntity->findOne(['group_id' => $group['group_id'], 'user_id' => $data['id']]);
                $this->UserGroupEntity->remove($user_map['id']);
            }
            else
            {
                $key = array_search($group['group_id'], $groups_update);
                unset($groups_update[$key]);
            }
        }

        foreach($groups_update as $group)
        {
            $this->UserGroupEntity->add([
                'user_id' => $id,
                'group_id' => $group,
            ]);
        }

        return true;
    }

    public function addUserMap($newid, $groups)
    {
        if (!$newid)
        {
            return false;
        }
        
        if ($groups)
        {
            foreach($groups as $group)
            {
                $this->UserGroupEntity->add([
                    'user_id' => $newid,
                    'group_id' => $group,
                ]);
            }
        }
        return true;
    }

    public function checkAccessGroup($id, $access = [])
    {
        $groups = $this->UserEntity->getGroups($this->user->get('id'));
        $check = false;
        $user_groups = [];

        foreach($groups as $group)
        {
            if ($group['group_id'] == $id)
            {
                $check = true;
            }
            else
            {
                $user_groups[] = $group['id'];
            }
        }

        if (!$check)
        {
            return true;
        }
        
        $user_access = $this->UserModel->getAccessByGroup($user_groups);
        $user_access = array_merge($user_access, $access);
        
        if (!in_array('user_manager', $user_access) || !in_array('usergroup_manager', $user_access))
        {
            // return false;
        }

        return true;
    }

    public function validate($data)
    {
        if (!$data || !is_array($data))
        {
            return false;
        }

        if(!empty($data['name'])) 
        {
            $find = $this->GroupEntity->findOne(['name' => $data['name']]);
            if ($find && (isset($data['id']) && $find['id'] != $data['id']))
            {
                $this->error = "Error: Group Name already exists";
                return false;
            }
        } 
        else 
        {
            $this->error = "Error: Group name can't empty";
            return false;
        }

        return true;
    }

    public function add($data)
    {
        $data = $this->GroupEntity->bind($data);

        $try = $this->validate($data);
        if (!$try)
        {
            return false;
        }

        $try = $this->GroupEntity->add($data);

        return $try;
    }

    public function update($data)
    {
        $data = $this->GroupEntity->bind($data);
        $try = $this->validate($data);
        if (!$try || !isset($data['id']) || !$data['id'])
        {
            return false;
        }

        $try = $this->GroupEntity->update($data);

        return $try;
    }

    public function remove($id)
    {
        if (!$id) return false;
        $try = $this->GroupEntity->remove($id);
        if ($try)
        {
            $this->UserGroupModel->removeByGroup($id);
        }
        
        return $try;
    }
}
