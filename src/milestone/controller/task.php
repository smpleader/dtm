<?php namespace DTM\milestone\controllers;

use SPT\Web\ControllerMVVM;
use SPT\Response;

class task extends ControllerMVVM 
{
    public function detail()
    {
        
        $urlVars = $this->request->get('urlVars');
        $id = (int) $urlVars['id'];
        
        $request_id = $this->validateRequestID();
        $exist = $this->TaskEntity->findByPK($id);
        if(!empty($id) && !$exist) 
        {
            $this->session->set('flashMsg', "Invalid Task");
            return $this->app->redirect(
                $this->router->url('detail-request/'. $request_id)
            );
        }
        $this->app->set('layout', 'backend.task.form');
        $this->app->set('page', 'backend');
        $this->app->set('format', 'html');
    }

    public function list()
    {
                $urlVars = $this->request->get('urlVars');
        $request_id = (int) $urlVars['request_id'];
        $search = trim($this->request->post->get('search_task', '', 'string'));
        $where = ['request_id' => $request_id];
        if ($search)
        {
            $where[] = "(`title` LIKE '%".$search."%' OR 
                        `url` LIKE '%".$search."%')";

        }

        $result = $this->TaskEntity->list( 0, 0, $where, 0);
        $result = $result ? $result : [];

        $this->app->set('format', 'json');
        $this->set('result', $result);
        return ;
    }

    public function add()
    {
                $request_id = $this->validateRequestID();

        $title = $this->request->post->get('title', '', 'string');
        $url = $this->request->post->get('url', '', 'string');
        $status = $this->request->post->get('status', 0, 'string');

        // TODO: validate new add
        $newId =  $this->TaskEntity->add([
            'request_id' => $request_id,
            'title' => $title,
            'status' => $status,
            'url' => $url,
            'created_by' => $this->user->get('id'),
            'created_at' => date('Y-m-d H:i:s'),
            'modified_by' => $this->user->get('id'),
            'modified_at' => date('Y-m-d H:i:s')
        ]);
        
        if( !$newId )
        {
            $this->app->set('format', 'json');
            $this->set('result', 'fail');
            $this->set('message', 'Create Task Failed!');
            return ;
        }
        else
        {
            $this->app->set('format', 'json');
            $this->set('result', 'ok');
            $this->set('message', 'Create Task Successfully!');
            return ;
        }
    }

    public function update()
    {
        $ids = $this->validateID(); 
        $request_id = $this->validateRequestID();
        
        // TODO valid the request input

        if(is_numeric($ids) && $ids)
        {
            $title = $this->request->post->get('title', '', 'string');
            $url = $this->request->post->get('url', '', 'string');
            $status = $this->request->post->get('status', 0, 'string');

            $try = $this->TaskEntity->update([
                'title' => $title,
                'url' => $url,
                'status' => $status,
                'id' => $ids,
                'created_by' => $this->user->get('id'),
                'created_at' => date('Y-m-d H:i:s'),
                'modified_by' => $this->user->get('id'),
                'modified_at' => date('Y-m-d H:i:s')
            ]);
            
            if($try) 
            {
                $this->app->set('format', 'json');
                $this->set('result', 'ok');
                $this->set('message', 'Update Task Successfully!');
                return ;
            }
            else
            {
                $this->app->set('format', 'json');
                $this->set('result', 'ok');
                $this->set('message', 'Error: Update Task Failed!');
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
                if( $this->TaskEntity->remove( $id ) )
                {
                    $count++;
                }
            }
        }
        elseif( is_numeric($ids) )
        {
            if( $this->TaskEntity->remove($ids ) )
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
                $request_id = $this->validateRequestID();
        $urlVars = $this->request->get('urlVars');
        $id = isset($urlVars['id']) ? (int) $urlVars['id'] : 0;

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

}