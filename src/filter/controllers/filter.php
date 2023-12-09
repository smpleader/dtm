<?php
namespace DTM\filter\controllers;

use SPT\Response;
use SPT\Web\ControllerMVVM;

class filter extends ControllerMVVM
{
    public function list()
    {
        $this->app->set('page', 'backend');
        $this->app->set('format', 'html');
        $this->app->set('layout', 'filter.list');
    }

    public function detail()
    {
        $this->app->set('page', 'backend');
        $this->app->set('format', 'html');
        $this->app->set('layout', 'filter.form');
    }

    public function add()
    {
        $save_close = $this->request->post->get('save_close', '', 'string');
        $data = [
            'user_id' => $this->user->get('id'),
            'shortcut_id' => 0,
            'name' => $this->request->post->get('name', '', 'string'),
            'select_object' => $this->request->post->get('select_object', '', 'string'),
            'start_date' => $this->request->post->get('start_date', '', 'string'),
            'end_date' => $this->request->post->get('end_date', '', 'string'),
            'tags' => $this->request->post->get('tags', [], 'array'),
            'creator' => $this->request->post->get('creator', [], 'array'),
            'ignore_creator' => $this->request->post->get('ignore_creator', [], 'array'),
            'permission' => $this->request->post->get('permission', [], 'array'),
            'shortcut_name' => $this->request->post->get('shortcut_name', '', 'string'),
            'shortcut_link' => $this->request->post->get('shortcut_link', '', 'string'),
            'shortcut_group' => $this->request->post->get('shortcut_group', '', 'string'),
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->user->get('id'),
            'modified_at' => date('Y-m-d H:i:s'),
            'modified_by' => $this->user->get('id'),
        ];

        $try = $this->FilterModel->add($data);

        $message = $try ? 'Create Successfully!' : 'Error: '. $this->FilterModel->getError();

        $this->session->set('flashMsg', $message);
        if (!$try)
        {
            return $this->app->redirect(
                $this->router->url('my-filter/edit/0')
            );
        }
        else
        {
            return $this->app->redirect(
                $this->router->url($save_close ? 'my-filters' : 'my-filter/edit/'. $try)
            );
        }
    }

    public function update()
    {
        $id = $this->validateID(); 

        if(is_numeric($id) && $id)
        {
            $save_close = $this->request->post->get('save_close', '', 'string');
            $data = [
                'id' => $id,
                'user_id' => $this->user->get('id'),
                'name' => $this->request->post->get('name', '', 'string'),
                'select_object' => $this->request->post->get('select_object', '', 'string'),
                'start_date' => $this->request->post->get('start_date', null, 'string'),
                'end_date' => $this->request->post->get('end_date', null, 'string'),
                'tags' => $this->request->post->get('tags', [], 'array'),
                'creator' => $this->request->post->get('creator', [], 'array'),
                'ignore_creator' => $this->request->post->get('ignore_creator', [], 'array'),
                'permission' => $this->request->post->get('permission', [], 'array'),
                'shortcut_name' => $this->request->post->get('shortcut_name', '', 'string'),
                'shortcut_link' => $this->request->post->get('shortcut_link', '', 'string'),
                'shortcut_group' => $this->request->post->get('shortcut_group', '', 'string'),
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => $this->user->get('id'),
                'modified_at' => date('Y-m-d H:i:s'),
                'modified_by' => $this->user->get('id'),
            ];
            
            $try = $this->FilterModel->update($data);
            $message = $try ? 'Update Successfully!' : 'Error: '. $this->FilterModel->getError();
            
            $this->session->set('flashMsg', $message);
            if (!$try)
            {
                return $this->app->redirect(
                    $this->router->url('my-filter/edit/'. $id)
                );
            }
            else
            {
                return $this->app->redirect(
                    $this->router->url($save_close ? 'my-filters' : 'my-filter/edit/'. $id)
                );
            }
        }

        $this->session->set('flashMsg', 'Error: Invalid Task!');
        return $this->app->redirect(
            $this->router->url('my-filters')
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
                if( $this->FilterModel->remove( $id ) )
                {
                    $count++;
                }
            }
        }
        elseif( is_numeric($ids) )
        {
            if( $this->FilterModel->remove($ids ) )
            {
                $count++;
            }
        }  
        

        $this->session->set('flashMsg', $count.' deleted record(s)');
        return $this->app->redirect(
            $this->router->url('my-filters'), 
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

            $this->session->set('flashMsg', 'Invalid Filter');
            return $this->app->redirect(
                $this->router->url('my-filters'),
            );
        }

        return $id;
    }

    public function filter()
    {
        $urlVars = $this->request->get('urlVars');
        $filter_name = $urlVars && $urlVars['filter_name'] ? $urlVars['filter_name'] : '';
        
        $check = $this->FilterModel->checkFilterName($filter_name);
        
        if (!$check)
        {
            $this->app->raiseError('Invalid request');
        }
        $this->session->set('link_back_note', 'my-filter/'. $filter_name);

        $this->app->set('filter_id', $check['id']);

        $this->app->set('page', 'backend');
        $this->app->set('format', 'html');
        $this->app->set('layout', 'note.list');
    }
}