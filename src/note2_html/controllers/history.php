<?php
/**
 * SPT software - homeController
 *
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: Just a basic controller
 *
 */

namespace DTM\note2_html\controllers;

use SPT\Response;
use DTM\note2\libraries\NoteController;

class history extends NoteController
{
    public function detail()
    {
        $urlVars = $this->request->get('urlVars');
        $id = (int) $urlVars['id'];

        $exist = $this->HistoryEntity->findByPK($id);

        if(!empty($id) && !$exist)
        {
            $this->session->set('flashMsg', "Invalid note");
            return $this->app->redirect(
                $this->router->url('notes')
            );
        }

        $this->app->set('layout', 'backend.history');
        $this->app->set('page', 'backend');
        $this->app->set('format', 'html');
    }

    public function rollback()
    {
        $id = $this->validateID();
        $try = $this->NoteHtmlModel->rollback($id);

        $msg = $try ? 'Rollback Successfully' : $this->NoteHtmlModel->getError();

        $link = $try ? 'note2/detail/'. $try : 'history/note-html/'.$id;
    
        $this->session->set('flashMsg', $msg);
        return $this->app->redirect(
            $this->router->url($link)
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
                $this->router->url('note2'),
            );
        }

        return $id;
    }
}