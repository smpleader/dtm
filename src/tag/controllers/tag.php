<?php
/**
 * SPT software - homeController
 *
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: Just a basic controller
 *
 */

namespace DTM\tag\controllers;

use SPT\Web\ControllerMVVM;

class tag extends ControllerMVVM
{
    public function list()
    {
        $this->app->set('page', 'backend');
        $this->app->set('format', 'html');
        $this->app->set('layout', 'backend.tag.list');
    }

    public function add()
    {
        $data = [
            'name' => $this->request->post->get('name', '', 'string'),
            'description' => $this->request->post->get('description', '', 'string'),
            'parent_id' => $this->request->post->get('parent_id', 0, 'int'),
        ];

        $data = $this->TagModel->validate($data);
        if (!$data)
        {
            return $this->app->redirect(
                $this->router->url('tags')
            );
        }
        
        $try = $this->TagModel->add($data);

        if( !$try )
        {
            $msg = 'Error: Create Failed!';
            $this->session->set('flashMsg', $msg);
            return $this->app->redirect(
                $this->router->url('tags')
            );
        }
        else
        {
            $this->session->set('flashMsg', 'Create Successfully!');
            return $this->app->redirect(
                $this->router->url('tags')
            );
        }
    }

    public function update()
    {
        $id = $this->validateID(); 

        if(is_numeric($id) && $id)
        {
            $data = [
                'name' => $this->request->post->get('name', '', 'string'),
                'description' => $this->request->post->get('description', '', 'string'),
                'parent_id' => $this->request->post->get('parent_id', 0, 'int'),
                'id' => $id,
            ];
        
            $data = $this->TagModel->validate($data);
            if (!$data)
            {
                return $this->app->redirect(
                    $this->router->url('tags')
                );
            }
            
            $try = $this->TagModel->update($data);
            
            if($try) 
            {
                $this->session->set('flashMsg', 'Update Successfully!');
                return $this->app->redirect(
                    $this->router->url('tags')
                );
            }
            else
            {
                $this->session->set('flashMsg', 'Error: Update Failed!');
                return $this->app->redirect(
                    $this->router->url('tags')
                );
            }
        }

        $this->session->set('flashMsg', 'Error: Invalid Task!');
        return $this->app->redirect(
            $this->router->url('tags')
        );
    }

    public function delete()
    {
        $ids = $this->validateID();
        $count = 0;
        if( is_array($ids))
        {
            foreach($ids as $id)
            {
                //Delete file in source
                if( $this->TagModel->remove( $id ) )
                {
                    $count++;
                }
            }
        }
        elseif( is_numeric($ids) )
        {
            if( $this->TagModel->remove($ids ) )
            {
                $count++;
            }
        }  
        

        $this->session->set('flashMsg', $count.' deleted record(s)');
        return $this->app->redirect(
            $this->router->url('tags'), 
        );
    }

    public function validateID()
    {
        $urlVars = $this->request->get('urlVars');
        $id = $urlVars ? (int) $urlVars['id'] : 0;

        if(empty($id))
        {
            $ids = $this->request->post->get('ids', [], 'array');
            if(count($ids)) return $ids;

            $this->session->set('flashMsg', 'Invalid Tag');
            return $this->app->redirect(
                $this->router->url('tags'),
            );
        }

        return $id;
    }

    public function search()
    {
        $search = trim($this->request->get->get('search', '', 'string'));
        $ignores = $this->request->get->get('ignores', [], 'array');

        $data = $this->TagModel->search($search, $ignores);

        $this->app->set('format', 'json');
        $this->set('status' , 'success');
        $this->set('data' , $data);
        $this->set('message' , '');
        return;
    }
}