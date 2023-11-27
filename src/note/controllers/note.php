<?php
namespace DTM\note\controllers;

use SPT\Response;
use SPT\Web\ControllerMVVM;

class note extends ControllerMVVM
{
    public function list()
    {
        $this->session->set('link_back_note', 'notes');
        $this->app->set('page', 'backend');
        $this->app->set('format', 'html');
        $this->app->set('layout', 'backend.note.list');
    }

    public function delete()
    {
        $ids = $this->validateID();
        $mode = $this->app->get('filter');
        $link = $mode == 'my-note' ? 'my-filter/my-notes' : 'notes';

        $count = 0;
        $error_msg = '';
        if( is_array($ids))
        {
            foreach($ids as $id)
            {
                //Delete file in source
                if( $this->NoteModel->remove( $id ) )
                {
                    $count++;
                }
                else
                {
                    $error_msg = $this->NoteModel->getError();
                }
            }
        }
        elseif( is_numeric($ids) )
        {
            if( $this->NoteModel->remove($ids ) )
            {
                $count++;
            }
            else
            {
                $error_msg = $this->NoteModel->getError();
            }
        }


        $this->session->set('flashMsg', $error_msg ? $error_msg : $count.' deleted record(s)');
        return $this->app->redirect(
            $this->router->url($link),
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

            $this->session->set('flashMsg', 'Invalid note');
            return $this->app->redirect(
                $this->router->url('my-notes'),
            );
        }

        return $id;
    }

    public function search()
    {
        $search = trim($this->request->get->get('search', '', 'string'));
        $type = trim($this->request->get->get('type', '', 'string'));
        $ignore = $this->request->get->get('ignore', '', 'string');
        
        $list = $this->NoteModel->searchAjax($search, $ignore, $type);

        $this->app->set('format', 'json');
        $this->set('status' , 'success');
        $this->set('data' , $list);
        $this->set('message' , '');
        return;
    }

    public function request()
    {
        $urlVars = $this->request->get('urlVars');
        $id = (int) $urlVars['id'];
       
        $list = $this->NoteModel->getRequest($id);
        
        $this->app->set('format', 'json');
        $this->set('status' , 'success');
        $this->set('data' , $list);
        $this->set('message' , '');
        return;
    }

    public function trash()
    {
        $this->app->set('page', 'backend');
        $this->app->set('format', 'html');
        $this->app->set('layout', 'backend.note.trash');
    }

    public function hardDelete()
    {
        $ids = $this->validateID();
        $count = 0;
        $error_msg = '';
        if( is_array($ids))
        {
            foreach($ids as $id)
            {
                //Delete file in source
                if( $this->NoteModel->remove( $id, true ) )
                {
                    $count++;
                }
                else
                {
                    $error_msg = $this->NoteModel->getError();
                }
            }
        }
        elseif( is_numeric($ids) )
        {
            if( $this->NoteModel->remove($ids, true ) )
            {
                $count++;
            }
            else
            {
                $error_msg = $this->NoteModel->getError();
            }
        }


        $this->session->set('flashMsg', $error_msg ? $error_msg : $count.' deleted record(s)');
        return $this->app->redirect(
            $this->router->url('notes/trash'),
        );
    }

    public function restore()
    {
        $ids = $this->validateID();
        $mode = $this->app->get('filter');
        $link = $mode == 'my-note' ? 'my-filter/my-notes' : 'notes/trash';
        $count = 0;
        $error_msg = '';
        if( is_array($ids))
        {
            foreach($ids as $id)
            {
                //Delete file in source
                if( $this->NoteModel->restore( $id) )
                {
                    $count++;
                }
                else
                {
                    $error_msg = $this->NoteModel->getError();
                    break;
                }
            }
        }
        elseif( is_numeric($ids) )
        {
            if( $this->NoteModel->restore($ids) )
            {
                $count++;
            }
            else
            {
                $error_msg = $this->NoteModel->getError();
            }
        }


        $this->session->set('flashMsg', $error_msg ? $error_msg : $count.' restore record(s)');
        return $this->app->redirect(
            $this->router->url($link),
        );
    }
}