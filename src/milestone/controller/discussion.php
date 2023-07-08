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
            'message' => $this->request->post->get('message', '', 'string'),
            'request_id' => $request_id,
        ];

        $data = $this->DiscussionModel->validate($data);
        if (!$data)
        {
            $this->app->set('format', 'json');
            $this->set('result', 'fail');
            return;
        }

        $newId = $this->DiscussionModel->add($data);

        $msg = $newId ? 'Comment Successfully' : 'Comment Fail';
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