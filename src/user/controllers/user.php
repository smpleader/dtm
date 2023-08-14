<?php namespace DTM\user\controllers;

use SPT\Response;
use SPT\Web\ControllerMVVM;

class user extends ControllerMVVM 
{
    public function gate()
    {
        if( $this->user->get('id') )
        {
            return $this->app->redirect(
                $this->router->url('users')
            );
        }
        $this->app->set('format', 'html');
        $this->app->set('layout', 'backend.user.login');
        $this->app->set('page', 'backend-full');
    }

    public function login()
    {
        $redirectAfterLogin = $this->config->redirectAfterLogin ? $this->config->redirectAfterLogin : ''; 
        if ($this->user->get('id'))
        {
            return $this->app->redirect(
                $this->router->url($redirectAfterLogin)
            );
        }

        $result = $this->UserModel->login(
            $this->request->post->get('username', '', 'string'),
            $this->request->post->get('password', '', 'string')
        );

        if (!$result)
        {
            $this->session->set('flashMsg', 'Error: '. $this->UserModel->getError());
        }
        
        $link = $result ? $this->router->url($redirectAfterLogin) : $this->router->url('login');
        return $this->app->redirect(
            $link
        );
    }

    public function detail()
    {
        
        $urlVars = $this->request->get('urlVars');
        $id = (int) $urlVars['id'];

        $existUser = $this->UserEntity->findByPK($id);
        if(!empty($id) && !$existUser) 
        {
            $this->session->set('flashMsg', "Invalid user");
            return $this->app->redirect(
                $this->router->url('users')
            );
        }

        $this->app->set('layout', 'backend.user.form');
        $this->app->set('page', 'backend');
        $this->app->set('format', 'html');
    }

    public function profile()
    {
        
        $this->app->set('layout', 'backend.user.profile');
        $this->app->set('page', 'backend');
        $this->app->set('format', 'html');
    }

    public function saveProfile()
    {
        $id = $this->user->get('id'); 
        $save_close = $this->request->post->get('save_close', '', 'string');
       
        $password = $this->request->post->get('password', '');
        $repassword = $this->request->post->get('confirm_password', '');
        
        $user = [
            'name' => $this->request->post->get('name', '', 'string'),
            'username' => $this->request->post->get('username', '', 'string'),
            'email' => $this->request->post->get('email', '', 'string'),
            'status' => 1,
            'modified_by' => $this->user->get('id'),
            'modified_at' => date('Y-m-d H:i:s'),
            'id' => $id,
        ];

        if($password) 
        {
            $user['password'] = $password;
            $user['confirm_password'] = $repassword;
        }
       

        $try = $this->UserModel->update( $user );

        if($try) 
        {
            $this->session->set('flashMsg', 'Updated Successfully');
            $link = $save_close ? '' : 'profile';
            return $this->app->redirect(
                $this->router->url($link)
            );
        }
        else
        {
            $msg = 'Error: Updated Fail';
            $this->session->set('flashMsg', $this->UserModel->getError());
            return $this->app->redirect(
                $this->router->url('profile')
            );
        }
    }

    public function list()
    {
        $this->app->set('page', 'backend');
        $this->app->set('format', 'html');
        $this->app->set('layout', 'backend.user.list');
    }

    public function logout()
    {
        $this->user->logout();

        return $this->app->redirect(
            $this->router->url('login')
        );
    }

    public function add()
    {
        $save_close = $this->request->post->get('save_close', '', 'string');
        $groups = $this->request->post->get('groups', [], 'array');
        
        // TODO: validate new add
        $data = [
            'name' => $this->request->post->get('name', '', 'string'),
            'username' => $this->request->post->get('username', '' , 'string'),
            'email' => $this->request->post->get('email', '' , 'string'),
            'password' => $this->request->post->get('password', ''),
            'confirm_password' => $this->request->post->get('confirm_password', ''),
            'status' => $this->request->post->get('status', 0),
            'created_by' => $this->user->get('id'),
            'created_at' => date('Y-m-d H:i:s'),
            'modified_by' => $this->user->get('id'),
            'modified_at' => date('Y-m-d H:i:s')
        ];
        $newId = $this->UserModel->add($data);

        if( !$newId )
        {
            $this->session->setform('user', $data);
            $this->session->set('flashMsg', 'Error: '. $this->UserModel->getError());
            return $this->app->redirect(
                $this->router->url('user/0')
            );
        }
        else
        {
            $this->UserGroupModel->addUserMap($newId, $groups);
            $this->session->set('flashMsg', 'Created Successfully');
            $link = $save_close ? 'users' : 'user/'. $newId;
            return $this->app->redirect(
                $this->router->url($link)
            );
        }
    }

    public function update()
    {
        $ids = $this->validateID(); 
        $save_close = $this->request->post->get('save_close', '', 'string');

        // TODO valid the request input
        $groups = $this->request->post->get('groups', [], 'array');
        $access = $this->UserModel->getAccessByGroup($groups);

        if(is_numeric($ids) && $ids)
        {
            if ($ids == $this->user->get('id') && (!in_array('user_manager', $access) || !in_array('usergroup_manager', $access)))
            {
                $this->session->set('flashMsg', 'Error: You can\'t delete your access group');
                return $this->app->redirect(
                    $this->router->url('user/'. $ids)
                );
            }

            $user = [
                'name' => $this->request->post->get('name', '', 'string'),
                'username' => $this->request->post->get('username', '' , 'string'),
                'email' => $this->request->post->get('email', '', 'string'),
                'status' => $this->request->post->get('status', 0),
                'modified_by' => $this->user->get('id'),
                'modified_at' => date('Y-m-d H:i:s'),
                'id' => $ids,
            ];

            $password = $this->request->post->get('password', '');
            if($password) {
                $user['password'] = $this->request->post->get('password', '');
                $user['confirm_password'] = $this->request->post->get('confirm_password', '');
            }
            
            $try = $this->UserModel->update( $user );

            if($try) 
            {
                $this->UserGroupModel->updateUserMap($ids, $groups);
                $this->session->set('flashMsg', 'Updated Successfully');
                $link = $save_close ? 'users' : 'user/'. $ids;
                return $this->app->redirect(
                    $this->router->url($link)
                );
            }
            else
            {
                $this->session->set('flashMsg', 'Error: '. $this->UserModel->getError());
                return $this->app->redirect(
                    $this->router->url('user/'. $ids)
                );
            }
        }
    }

    public function delete()
    {
        $userID = $this->validateID();
        
        $count = 0;
        if( is_array($userID))
        {
            foreach($userID as $id)
            {
                if( $id == $this->user->get('id') )
                {
                    $this->session->set('flashMsg', 'Error: You can\'t delete yourself.');
                    return $this->app->redirect(
                        $this->router->url('users'),
                    );
                }

                //Delete file in source
                if( $this->UserModel->remove( $id ) )
                {
                    $count++;
                }
            }
        }
        elseif( is_numeric($userID) )
        {
            if( $userID === $this->user->get('id') )
            {
                $this->session->set('flashMsg', 'Error: You can\'t delete yourself.');
                return $this->app->redirect(
                    $this->router->url()
                );
            }
            //Delete file in source
            if( $this->UserModel->remove($userID ) )
            {
                $count++;
            }
        }  
        

        $this->session->set('flashMsg', $count.' deleted record(s)');
        return $this->app->redirect(
            $this->router->url('users'), 
        );
    }

    public function validateID()
    {
        $urlVars = $this->request->get('urlVars');
        $id = $urlVars ? (int) $urlVars['id'] : [];

        if(empty($id))
        {
            $ids = $this->request->post->get('ids', [], 'array');
            if(count($ids)) return $ids;

            $this->session->set('flashMsg', 'Invalid user');
            return $this->app->redirect(
                $this->router->url('users'),
            );
        }

        return $id;
    }

}