<?php
/**
 * SPT software - homeController
 *
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: Just a basic controller
 *
 */

namespace DTM\note2_presenter\controllers;

use SPT\Response;
use DTM\note2\libraries\NoteController;

class note extends NoteController
{
    public function newform()
    {
        $this->app->set('layout', 'backend.form');
        $this->app->set('page', 'backend');
        $this->app->set('format', 'html');
    }

    public function detail()
    {
        $this->app->set('layout', 'backend.form');
        $this->app->set('page', 'backend');
        $this->app->set('format', 'html');
    }

    public function preview()
    {
        $this->app->set('layout', 'backend.note.preview');
        $this->app->set('page', 'backend');
        $this->app->set('format', 'html');
    }


    public function list()
    {
        $this->app->set('page', 'backend');
        $this->app->set('format', 'html');
        $this->app->set('layout', 'backend.note.list');
    }

    public function add()
    {
        //check title sprint
        $data = [
            'title' => $this->request->post->get('title', '', 'string'),
            'data' => $this->request->post->get('data', '', 'string'),
            'notice' => $this->request->post->get('notice', '', 'string'),
        ];
        
        $save_close = $this->request->post->get('save_close', '', 'string');

        $newId = $this->NotePresenterModel->add($data);
        if (!$newId)
        {
            $this->session->set('flashMsg', 'Create failed.'. $this->NotePresenterModel->getError()); 
            return $this->app->redirect(
                $this->router->url('new-note2/presenter')
            );
        }

        $this->session->set('flashMsg', 'Create Successfully'); 
        $link = $save_close ? $this->router->url('note2') : $this->router->url('note2/detail/'. $newId);
        return $this->app->redirect(
            $link
        );
    }

    public function update()
    {
        $id = $this->validateID();

        // TODO valid the request input

        if(is_numeric($id) && $id)
        {
            $data = [
                'title' => $this->request->post->get('title', '', 'string'),
                'data' => $this->request->post->get('data', '', 'array'),
                'notice' => $this->request->post->get('notice', '', 'string'),
                'id' => $id,
            ];

            $save_close = $this->request->post->get('save_close', '', 'string');

            $try = $this->NotePresenterModel->update($data);
            
            if(!$try)
            {
                $this->session->set('flashMsg', 'Create failed.'. $this->NotePresenterModel->getError()); 
                return $this->app->redirect(
                    $this->router->url('note2/detail/'. $id)
                );
            }

            $this->session->set('flashMsg', 'Updated successfully');
            $link = $save_close ? 'note2' : 'note2/detail/'. $id;

            return $this->app->redirect(
                $this->router->url($link)
            );
        }

        $this->session->set('flashMsg', 'Invalid Note');

        return $this->app->redirect(
            $this->router->url('note2')
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
                if( $this->NotePresenterModel->remove( $id ) )
                {
                    $count++;
                }
            }
        }
        elseif( is_numeric($ids) )
        {
            if( $this->NotePresenterModel->remove($ids ) )
            {
                $count++;
            }
        }


        $this->session->set('flashMsg', $count.' deleted record(s)');
        return $this->app->redirect(
            $this->router->url('note2'),
        );
    }

    public function validateID()
    {
        
        $urlVars = $this->request->get('urlVars');
        $id = (int) $urlVars['id'];

        if(empty($id))
        {
            $ids = $this->request->post->get('ids', [], 'array');
            if(count($ids)) return $ids;

            $this->session->set('flashMsg', 'Invalid note');
            return $this->app->redirect(
                $this->router->url('note2'),
            );
        }

        return $id;
    }
}