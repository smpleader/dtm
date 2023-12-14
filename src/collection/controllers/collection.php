<?php
namespace DTM\collection\controllers;

use SPT\Response;
use SPT\Web\ControllerMVVM;

class collection extends ControllerMVVM
{
    public function list()
    {
        $this->app->set('page', 'backend');
        $this->app->set('format', 'html');
        $this->app->set('layout', 'collection.list');
    }

    public function detail()
    {
        $this->app->set('page', 'backend');
        $this->app->set('format', 'html');
        $this->app->set('layout', 'collection.form');
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
            'filters' => $this->request->post->get('filters', [], 'array'),
            'tags' => $this->request->post->get('tags', [], 'array'),
            'creator' => $this->request->post->get('creator', [], 'array'),
            'assignment' => $this->request->post->get('assignment', [], 'array'),
            'shares' => $this->request->post->get('shares', [], 'array'),
            'shortcut_name' => $this->request->post->get('shortcut_name', '', 'string'),
            'shortcut_link' => $this->request->post->get('shortcut_link', '', 'string'),
            'shortcut_group' => $this->request->post->get('shortcut_group', '', 'string'),
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->user->get('id'),
            'modified_at' => date('Y-m-d H:i:s'),
            'modified_by' => $this->user->get('id'),
        ];

        $try = $this->CollectionModel->add($data);

        $message = $try ? 'Create Successfully!' : 'Error: '. $this->CollectionModel->getError();

        $this->session->set('flashMsg', $message);
        if (!$try)
        {
            return $this->app->redirect(
                $this->router->url('collection/edit/0')
            );
        }
        else
        {
            return $this->app->redirect(
                $this->router->url($save_close ? 'collections' : 'collection/edit/'. $try)
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
                'filters' => $this->request->post->get('filters', [], 'array'),
                'tags' => $this->request->post->get('tags', [], 'array'),
                'creator' => $this->request->post->get('creator', [], 'array'),
                'assignment' => $this->request->post->get('assignment', [], 'array'),
                'shares' => $this->request->post->get('shares', [], 'array'),
                'shortcut_name' => $this->request->post->get('shortcut_name', '', 'string'),
                'shortcut_link' => $this->request->post->get('shortcut_link', '', 'string'),
                'shortcut_group' => $this->request->post->get('shortcut_group', '', 'string'),
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => $this->user->get('id'),
                'modified_at' => date('Y-m-d H:i:s'),
                'modified_by' => $this->user->get('id'),
            ];
            
            $try = $this->CollectionModel->update($data);
            $message = $try ? 'Update Successfully!' : 'Error: '. $this->CollectionModel->getError();
            
            $this->session->set('flashMsg', $message);
            if (!$try)
            {
                return $this->app->redirect(
                    $this->router->url('collection/edit/'. $id)
                );
            }
            else
            {
                return $this->app->redirect(
                    $this->router->url($save_close ? 'collections' : 'collection/edit/'. $id)
                );
            }
        }

        $this->session->set('flashMsg', 'Error: Invalid Task!');
        return $this->app->redirect(
            $this->router->url('collections')
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
                if( $this->CollectionModel->remove( $id ) )
                {
                    $count++;
                }
            }
        }
        elseif( is_numeric($ids) )
        {
            if( $this->CollectionModel->remove($ids ) )
            {
                $count++;
            }
        }  
        

        $this->session->set('flashMsg', $count.' deleted record(s)');
        return $this->app->redirect(
            $this->router->url('collections'), 
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
                $this->router->url('collections'),
            );
        }

        return $id;
    }

    public function filter()
    {
        $urlVars = $this->request->get('urlVars');
        $filter_name = $urlVars && $urlVars['filter_name'] ? $urlVars['filter_name'] : '';
        
        $check = $this->CollectionModel->checkFilterName($filter_name);
        
        if (!$check)
        {
            $this->app->raiseError('Invalid request');
        }
        $this->session->set('link_back_note', 'collection/'. $filter_name);

        $this->app->set('filter_id', $check['id']);

        $this->app->set('page', 'backend');
        $this->app->set('format', 'html');
        $this->app->set('layout', 'note.list');
    }

    public function getFilters()
    {
        $search = trim($this->request->get->get('search', '', 'string'));

        $data = $this->TagModel->search($search, [], ['(#__tags.parent_id <= 0 || #__tags.parent_id IS NULL)']);

        $this->app->set('format', 'json');
        $this->set('status' , 'success');
        $this->set('data' , $data);
        $this->set('message' , '');
        return;
    }
}