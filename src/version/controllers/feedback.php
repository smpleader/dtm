<?php namespace DTM\version\controllers;

use SPT\Web\ControllerMVVM;
use SPT\Response;

class feedback extends ControllerMVVM 
{
    public function list()
    {
        $this->validateVersionID();
        $this->app->set('page', 'backend');
        $this->app->set('format', 'html');
        $this->app->set('layout', 'backend.feedback.list');
    }

    public function validateVersionID()
    {
        
        $urlVars = $this->request->get('urlVars');
        $id = (int) $urlVars['version_id'];

        if(empty($id))
        {
            $this->session->set('flashMsg', 'Invalid Version');
            return $this->app->redirect(
                $this->router->url('versions'),
            );
        }

        return $id;
    }
}