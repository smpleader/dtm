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

class UserModel extends Base 
{ 
    use ErrorString; 

    public function getAccessByGroup($groups)
    {
        if (!is_array($groups))
        {
            return false;
        }
        $access = [];
        foreach($groups as $group)
        {
            $group_tmp = $this->GroupEntity->findByPK($group);
            if ($group_tmp)
            {
                $access_tmp = $group_tmp['access'] ? json_decode($group_tmp['access'], true) : [];
                $access = array_merge($access, $access_tmp);
            }
        }

        return $access;
    }

    public function login($username, $passowrd)
    {
        if (!$passowrd || !$passowrd)
        {
            $this->error = 'Username and Password invalid.';
            return false;
        }

        $result = $this->user->login(
            $username,
            $passowrd
        );

        if ( $result )
        {
            if($result['status'] != 1) 
            {
                $this->error = 'User has been block';
                return false;
            }
            else
            {
                return true;
            }
        }
        else
        {
            $this->error = 'Username or Password Incorrect';
            return false;
        }
    }

    public function add($data)
    {
        $data = $this->UserEntity->bind($data);       
        if (!$data || !isset($data['readyNew']) || !$data['readyNew'])
        {
            return false;
        }
        unset($data['readyNew']);

        $newId =  $this->UserEntity->add($data);

        if (!$newId)
        {
            $this->error = $this->UserEntity->getError();
            return false;
        }

        $container = $this->app->getContainer();
        if ($container->exists('CollectionModel'))
        {
            $try = $this->CollectionModel->initCollection($newId);
        }

        return $newId;
    }

    public function update($data)
    {
        $data = $this->UserEntity->bind($data);   
        if (!$data || !isset($data['readyUpdate']) || !$data['readyUpdate'])
        {
            $this->error = $this->UserEntity->getError();
            return false;
        }
        unset($data['readyUpdate']);

        $try = $this->UserEntity->update($data);
        if (!$try)
        {
            $this->error = $this->UserEntity->getError();
            return false;
        }
        
        return $try;
    }

    public function  remove($id)
    {
        if (!$id) return false;
        $try = $this->UserEntity->remove($id);
        if ($try)
        {
            $this->UserGroupModel->removeByUser($id);
        }
        
        return $try;
    }
}
