<?php
/**
 * SPT software - homeController
 *
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: Just a basic controller
 *
 */

namespace DTM\note\controllers;

use SPT\Web\ControllerMVVM;

class Version extends ControllerMVVM {
    
    public function rollback()
    {
        $id = $this->validateID();
        $try = $this->NoteHistory->rollback($id);

        $msg = $try ? 'Update Successfully' : 'Error: Update Fail';
        $this->session->set('flashMsg', $msg);
        return $this->app->redirect(
            $this->router->url('note/'. $try)
        );
    }

    public function detail()
    {
        
        $urlVars = $this->request->get('urlVars');
        $id = (int) $urlVars['id'];

        $exist = $this->NoteHistoryEntity->findByPK($id);

        if(!empty($id) && !$exist)
        {
            $this->session->set('flashMsg', "Invalid note");
            return $this->app->redirect(
                $this->router->url('notes')
            );
        }

        $this->app->set('layout', 'backend.note_history.form');
        $this->app->set('page', 'backend');
        $this->app->set('format', 'html');
    }

    public function delete()
    {
        $ids = $this->validateID();

        $count = 0;
        if( is_numeric($ids) )
        {
            $version = $this->NoteHistoryEntity->findByPK($ids);
            $link = $version ? $this->router->url('note/'. $version['note_id']) : $this->router->url('notes');
            if( $this->NoteHistoryModel->remove($ids ) )
            {
                $count++;
            }
        }


        $this->session->set('flashMsg', $count.' deleted record(s)');
        return $this->app->redirect(
            $link
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

            $this->session->set('flashMsg', 'Invalid version');
            return $this->app->redirect(
                $this->router->url('notes'),
            );
        }

        return $id;
    }
}