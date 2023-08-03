<?php


namespace DTM\milestone\controllers;

use SPT\Web\ControllerMVVM;
use SPT\Response;

class discussion extends ControllerMVVM 
{
    public function add()
    {
        $request_id = $this->validateRequestID();
        
        $data = [
            'comment' => $this->request->post->get('message', '', 'string'),
            'object_id' => $request_id,
            'object' => 'request',
        ];

        $newId = $this->CommentModel->add($data);

        $msg = $newId ? 'Comment Successfully' : $this->CommentModel->getError();
        $this->app->set('format', 'json');
        $this->set('result', 'ok');
        $this->set('message', $msg);
        return;
    }

    public function validateRequestID()
    {
        
        $urlVars = $this->request->get('urlVars');
        $id = (int) $urlVars['request_id'];

        if(empty($id))
        {
            $this->session->set('flashMsg', 'Invalid Request');
            return $this->app->redirect(
                $this->router->url('milestones'),
            );
        }

        return $id;
    }

}