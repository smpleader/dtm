<?php namespace DTM\milestone\controllers;

use SPT\Web\ControllerMVVM;
use SPT\Response;

class Version extends ControllerMVVM 
{
    public function list()
    {
        $this->validateVersion();
        $urlVars = $this->request->get('urlVars');
        $request_id = (int) $urlVars['request_id'];
        $list = $this->RequestModel->getVersionNote($request_id);

        $this->app->set('format', 'json');
        $this->set('result', $list);
        return ;
    }

    public function add()
    {
        $this->validateVersion();
        //check title sprint
        $request_id = $this->validateRequestID();

        $log = $this->request->post->get('log', '', 'string');
        $data = [
            'log' => $log,
            'request_id' => $request_id,
        ];
        
        // TODO: validate new add
        $newId =  $this->RequestModel->addVersion($data);

        if( !$newId )
        {
            $msg = 'Error: Create Change log Failed!';
            $this->app->set('format', 'json');
            $this->set('result', 'fail');
            $this->set('message', $msg);
            return ;
        }
        else
        {
            $this->app->set('format', 'json');
            $this->set('result', 'ok');
            $this->set('message', 'Create Change log Successfully!');
            return ;
        }
    }

    public function update()
    {
        $ids = $this->validateID(); 
        $request_id = $this->validateRequestID();

        // TODO valid the request input

        if( is_array($ids) && $ids != null)
        {
            $this->app->set('format', 'json');
            $this->set('result', 'fail');
            $this->set('message', 'Invalid Version Note');
            return ;
        }
        if(is_numeric($ids) && $ids)
        {
            $data = [
                'log' => $this->request->post->get('log', '', 'string'),
                'id' => $ids,
            ];

            $try = $this->RequestModel->updateVersion($data);
            
            if($try) 
            {
                $this->app->set('format', 'json');
                $this->set('result', 'ok');
                $this->set('message', 'Update Change Log Successfully');
                return ;
            }
            else
            {
                $this->app->set('format', 'json');
                $this->set('result', 'fail');
                $this->set('message', 'Update Change Log Failed');
                return ;
            }
        }
    }

    public function delete()
    {
        $ids = $this->validateID();
        $request_id = $this->validateRequestID();

        $count = 0;
        if( is_array($ids))
        {
            foreach($ids as $id)
            {
                //Delete file in source
                if( $this->RequestModel->removeVersion( $id ) )
                {
                    $count++;
                }
            }
        }
        elseif( is_numeric($ids) )
        {
            if( $this->RequestModel->removeVersion($ids ) )
            {
                $count++;
            }
        }  
        
        $this->app->set('format', 'json');
        $this->set('result', 'ok');
        $this->set('message', $count.' deleted record(s)');
        return ;
    }

    public function validateID()
    {
        $this->validateVersion();
        $request_id = $this->validateRequestID();
        $urlVars = $this->request->get('urlVars');
        $id = (int) $urlVars['id'];

        if(empty($id))
        {
            $ids = $this->request->post->get('ids', [], 'array');
            if(count($ids)) return $ids;

            $this->session->set('flashMsg', 'Invalid Task');
            return $this->app->redirect(
                $this->router->url('detail-request/'. $request_id),
            );
        }

        return $id;
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

    public function validateVersion()
    {
        if (!$this->container->exists('VersionEntity'))
        {
            $this->session->set('flashMsg', 'Invalid Plugin Version');
            return $this->app->redirect(
                $this->router->url('admin')
            );
        }
    }
}