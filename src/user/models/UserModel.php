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

class UserModel extends Base 
{ 
    public function validate($data, $id = null)
    {
        if (!$data || !is_array($data))
        {
            return false;
        }
        
        $password = $data['password'];
        $username = $data['username'];
        $email = $data['email'];

        if(!empty($password)) 
        {
            $password = $password;
            if (strlen($password) < '6') 
            {
                $this->session->set('validate', "Error: Your Password Must Contain At Least 6 Characters!");
                return false;
            }
        } 
        elseif (!$id) 
        {
            $this->session->set('validate', "Error: Passwords cant't empty");
            return false;
        }

        // validate user name
        if(!empty($username)) 
        {
            $username = $username;
            $find = $this->UserEntity->findOne(['username' => $username]);
            if ($find && $find['id'] != $id)
            {
                $this->session->set('validate', "Error: Username already exists");
                return false;
            }
        } 
        else 
        {
            $this->session->set('validate', "Error: UserName cant't empty");
            return false;
        }

        //validate email
        if(!empty($email)) {
            $email = $email;
            $findEmail = $this->UserEntity->findOne(['email' => $email]);
            if ($findEmail && $findEmail['id'] != $id)
            {
                $this->session->set('validate', "Error: Email already exists");
                return false;
            }
        } else {
            $this->session->set('validate', "Error: Email can't empty");
            return false;
        }
        
        return true;
    }

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
            $this->session->set('flashMsg', 'Username and Password invalid.');
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
                $this->session->set('flashMsg', 'Error: User has been block');
                return false;
            }
            else
            {
                return true;
            }
        }
        else
        {
            $this->session->set('flashMsg', 'Username or Password Incorrect');
            return false;
        }
    }

    public function add($data)
    {
        if (!$data || !is_array($data) || !$data['username'])
        {
            return false;
        }

        $newId =  $this->UserEntity->add([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => md5($data['password']),
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

        $try = $this->UserEntity->update($data);

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
