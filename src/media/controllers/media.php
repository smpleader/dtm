<?php
/**
 * SPT software - homeController
 *
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: Just a basic controller
 *
 */

namespace DTM\media\controllers;

use SPT\Web\ControllerMVVM;

class media extends ControllerMVVM
{
    public function list()
    {
        $this->app->set('page', 'backend');
        $this->app->set('format', 'html');
        $this->app->set('layout', 'backend.list');
    }

    public function upload()
    {
        $data = [
            'file' => $this->request->file->get('file', [], 'array'),
        ];

        $try = $this->MediaModel->add($data);

        $message = $try ? 'Create Successfully!' : 'Error: '. $this->MediaModel->getError();

        $this->session->set('flashMsg', $message);
        return $this->app->redirect(
            $this->router->url('admin/media')
        );
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
        
            $try = $this->MediaModel->update($data);
            $message = $try ? 'Update Successfully!' : 'Error: '. $this->MediaModel->getError();
            
            $this->session->set('flashMsg', $message);
            return $this->app->redirect(
                $this->router->url('admin/media')
            );
        }

        $this->session->set('flashMsg', 'Error: Invalid Task!');
        return $this->app->redirect(
            $this->router->url('admin/media')
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
                if( $this->MediaModel->remove( $id ) )
                {
                    $count++;
                }
            }
        }
        elseif( is_numeric($ids) )
        {
            if( $this->MediaModel->remove($ids ) )
            {
                $count++;
            }
        }  
        

        $this->session->set('flashMsg', $count.' deleted record(s)');
        return $this->app->redirect(
            $this->router->url('admin/media'), 
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

            $this->session->set('flashMsg', 'Invalid Media');
            return $this->app->redirect(
                $this->router->url('admin/media'),
            );
        }

        return $id;
    }
}