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

    public function validate($data)
    {
        if (!$data || !is_array($data))
        {
            return false;
        }
        
        $id = isset($data['id']) ? $data['id'] : 0;
        $password = isset($data['password']) ? $data['password'] : '';
        $username = $data['username'];
        $email = $data['email'];

        if(!empty($password)) 
        {
            $password = $password;
            if (strlen($password) < '6') 
            {
                $this->error = "Your Password Must Contain At Least 6 Characters!";
                return false;
            }

            if ($password != $data['confirm_password'])
            {
                $this-> error = 'Confirm Password Invalid';
                return false;
            }
        } 
        elseif (!$id) 
        {
            $this->error = "Passwords can't empty";
            return false;
        }

        // validate user name
        if(!empty($username)) 
        {
            $username = $username;
            $find = $this->UserEntity->findOne(['username' => $username]);
            if ($find && $find['id'] != $id)
            {
                $this->error = "Username already exists";
                return false;
            }
        } 
        else 
        {
            $this->error = "UserName cant't empty";
            return false;
        }

        //validate email
        if(!empty($email)) {
            $email = $email;
            $findEmail = $this->UserEntity->findOne(['email' => $email]);
            if ($findEmail && $findEmail['id'] != $id)
            {
                $this->error = "Email already exists";
                return false;
            }
        } else {
            $this->error = "Email can't empty";
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
        $confirmPassword = isset($data['confirm_password']) ? $data['confirm_password'] : '';
        $data = $this->UserEntity->bind($data);
        $data['confirm_password'] = $confirmPassword;

        $try = $this->validate($data);
        
        if (!$try)
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
        $confirmPassword = isset($data['confirm_password']) ? $data['confirm_password'] : '';
        $data = $this->UserEntity->bind($data);
        $data['confirm_password'] = $confirmPassword;
        $try = $this->validate($data);

        if (!$try)
        {
            return false;
        }

        $data_update = [
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'status' => $data['status'],
            'modified_by' => $this->user->get('id'),
            'modified_at' => date('Y-m-d H:i:s'),
            'id' => $data['id'],
        ]; 

        if (isset($data['password']) && $data['password'])
        {
            $data_update['password'] = md5($data['password']);
        }

        $try = $this->UserEntity->update($data_update);

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
