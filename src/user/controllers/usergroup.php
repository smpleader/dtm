<?php namespace DTM\user\controllers;

use SPT\Response;
use SPT\Web\ControllerMVVM;

class usergroup extends ControllerMVVM 
{
    public function list()
    {
                
        $this->app->set('format', 'html');
        $this->app->set('layout', 'backend.usergroup.list');
        $this->app->set('page', 'backend');
    }

    public function detail()
    {
        $urlVars = $this->request->get('urlVars');
        $id = (int) $urlVars['id'];
        
        $this->app->set('layout', 'backend.usergroup.form');
        $this->app->set('page', 'backend');
        $this->app->set('format', 'html');
    } 

    public function add()
    {
        $save_close = $this->request->post->get('save_close', '', 'string');
        $name = $this->request->post->get('name', '', 'string');
        
        $status = $this->request->post->get('status', 0, 'string');

        $data = [
            'name' => $this->request->post->get('name', '', 'string'),
            'description' => $this->request->post->get('description', '', 'string'),
            'access' => json_encode($this->request->post->get('access', [], 'array')),
            'status' => $status,
            'created_by' => $this->user->get('id'),
            'created_at' => date('Y-m-d H:i:s'),
            'modified_by' => $this->user->get('id'),
            'modified_at' => date('Y-m-d H:i:s')
        ];
        $newId =  $this->UserGroupModel->add($data);
        
        if( !$newId )
        {
            $this->session->setform('usergroup', $data);
            $this->session->set('flashMsg', 'Error: '. $this->UserGroupModel->getError());
            return $this->app->redirect(
                $this->router->url('user-group/0')
            );
        }
        else
        {
            $this->session->set('flashMsg', 'Created Successfully');
            $link = $save_close ? 'user-groups' : 'user-group/'. $newId;
            return $this->app->redirect(
                $this->router->url($link)
            );
        }
    }

    public function update()
    {
        $sth = $this->validateId(); 
        $save_close = $this->request->post->get('save_close', '', 'string');
        $name = $this->request->post->get('name', '', 'string');
        
        if( is_numeric($sth) )
        {   

            $status = $this->request->post->get('status', 0, 'string');
            if (!$this->UserGroupModel->checkAccessGroup($sth, $this->request->post->get('access', [], 'array')))
            {
                $this->session->set('flashMsg', 'Error: You can\'t delete your access group!');
                return $this->app->redirect(
                    $this->router->url('user-group/'. $sth)
                );
            }

            $user = [
                'name' => $this->request->post->get('name', '', 'string'),
                'description' => $this->request->post->get('description', '', 'string'),
                'access' => json_encode($this->request->post->get('access', [], 'array')),
                'status' => $status,
                'modified_by' => $this->user->get('id'),
                'modified_at' => date('Y-m-d H:i:s'),
                'id' => $sth,
            ];
            $try = $this->UserGroupModel->update( $user );
            $msg = $try ? 'Updated Successfully' :  'Error: '. $this->UserGroupModel->getError();
            $this->session->set('flashMsg', $msg);
    
            if ($try)
            {
                $link = $save_close ? 'user-groups' : 'user-group/'. $sth;
                return $this->app->redirect(
                    $this->router->url($link)
                );
            }
            else
            {
                return $this->app->redirect(
                    $this->router->url('user-group/'.$sth)
                );
            }
            
        }

        $this->session->set('flashMsg', 'Error: Invalid request');
        return $this->app->redirect(
            $this->router->url('user-groups')
        );
    }

    public function delete()
    {
        $sth = $this->validateID();
        
        $count = 0;

        if( is_array($sth))
        {
            foreach($sth as $id)
            {
                if (!$this->UserGroupModel->checkAccessGroup($id, []))
                {
                    $this->session->set('flashMsg', 'Error: You can\'t delete your access group!');
                    return $this->app->redirect(
                        $this->router->url('user-groups')
                    );
                }

                if( $this->UserGroupModel->remove( $id ) )
                {
                    $count++;
                }
            }
        }
        elseif( is_numeric($sth) )
        {
            if (!$this->UserGroupModel->checkAccessGroup($sth, []))
            {
                $this->session->set('flashMsg', 'Error: You can\'t delete your access group!');
                return $this->app->redirect(
                    $this->router->url('user-groups')
                );
            }

            if( $this->UserGroupModel->remove($sth ) )
            {
                $count++;
            }
        }  

        $this->session->set('flashMsg', $count.' deleted record(s)');
        return $this->app->redirect( $this->router->url('user-groups'));
    }

    public function validateID()
    {
        $urlVars = $this->request->get('urlVars');
        $id = $urlVars ? (int) $urlVars['id'] : [];
        if(empty($id) && !$id)
        {
            $ids = $this->request->post->get('ids', [], 'array');
            if(count($ids)) return $ids;

            $this->session->set('flashMsg', 'Invalid user group');
            return $this->app->redirect(
                $this->router->url('user-groups')
            );
        }

        return $id;
    }
}
