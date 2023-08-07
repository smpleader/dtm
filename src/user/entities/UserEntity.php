<?php
/**
 * SPT software - Entity
 * 
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: Just a basic entity
 * 
 */

namespace DTM\user\entities;

use SPT\Storage\DB\Entity;

class UserEntity extends Entity
{
    protected $affix = 'User';
    protected $table = '#__users';
    protected $pk = 'id';

    public function getFields()
    {
        return [
                'id' => [
                    'type' => 'int',
                    'pk' => 1,
                    'option' => 'unsigned',
                    'extra' => 'auto_increment',
                ],
                'name' => [
                    'type' => 'varchar',
                    'limit' => 100,
                ],
                'username' => [
                    'type' => 'varchar',
                    'limit' => 100,
                ],
                'password' => [
                    // 'validate' => ['md5'],
                    'type' => 'varchar',
                    'limit' => 255,
                ],
                'email' => [
                    'type' => 'varchar',
                    'limit' => 255,
                ],
                'status' => [
                    'type' => 'tinyint',
                ],
                'created_at' => [
                    'type' => 'datetime',
                    'null' => 'YES',
                ],
                'created_by' => [
                    'type' => 'int',
                    'option' => 'unsigned',
                ],
                'modified_at' => [
                    'type' => 'datetime',
                    'null' => 'YES',
                ],
                'modified_by' => [
                    'type' => 'int',
                    'option' => 'unsigned',
                ],
        ];
    }

    public function togglePublishment( $id, $action)
    {
        $item = $this->findByPK($id);
        $status = $action == 'active' ? 1 : 0;
        return $this->db->table( $this->table )->update([
            'status' => $status,
        ], ['id' => $id ]);
    }
    
    public function getGroups($user_id)
    {
        $list = $this->db->select( 'usermap.user_id, usergroup.name as group_name, usergroup.id as group_id' )
                        ->table( '#__user_usergroup_map as usermap' )
                        ->join( 'LEFT JOIN #__user_groups as usergroup ON usergroup.id = usermap.group_id ')
                        ->where(['usermap.user_id = ' .$user_id]);

        return $list->list(0, 0);
    }

    public function validate( $data )
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
            $find = $this->findOne(['username' => $username]);
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
            $findEmail = $this->findOne(['email' => $email]);
            if ($findEmail && $findEmail['id'] != $id)
            {
                $this->error = "Email already exists";
                return false;
            }
        } else {
            $this->error = "Email can't empty";
            return false;
        }
        
        unset($data['confirm_password']);
        if (!$data['password'])
        {
            unset($data['password']);
        }
        else
        {
            $data['password'] = md5($data['password']);
        }

        return $data;
    }

    public function bind($data = [], $returnObject = false)
    {
        $row = [];
        $data = (array) $data;
        $fields = $this->getFields();
        $skips = isset($data['id']) && $data['id'] ? ['created_at', 'created_by'] : ['id'];
        foreach ($fields as $key => $field)
        {
            if (!in_array($key, $skips))
            {
                $default = isset($field['default']) ? $field['default'] : '';
                $row[$key] = isset($data[$key]) ? $data[$key] : $default;
            }
        }

        $row['confirm_password'] = isset($data['confirm_password']) ? $data['confirm_password'] : '';
    
        if (isset($data['id']) && $data['id'])
        {
            $row['readyUpdate'] = true;
        }
        else{
            $row['readyNew'] = true;
        }

        return $returnObject ? (object)$row : $row;
    }

}