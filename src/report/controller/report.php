<?php namespace DTM\report\controllers;

use SPT\Web\ControllerMVVM;
use SPT\Response;

class report extends ControllerMVVM 
{
    public function list()
    {
        $this->app->set('page', 'backend');
        $this->app->set('format', 'html');
        $this->app->set('layout', 'backend.diagram.list');
    }

    public function updateStatus()
    {
        $id = $this->request->post->get('id', '', 'string');
        $find = $this->DiagramEntity->findByPK($id);
        if (!$find)
        {
            $this->session->set('flashMsg', 'Invalid Report');
            return $this->app->redirect(
                $this->router->url('reports'),
            );
        }

        $try = $this->ReportModel->updateStatus([
            'id' => $id,
            'status' => $find['status'] ? 0 : 1,
        ]);

        $msg = $try ? 'Update Successfull' : 'Update Fail';
       
        $this->session->set('flashMsg', $msg);
        return $this->app->redirect(
            $this->router->url('reports'),
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
                $try = $this->ReportModel->remove($id);
                if ($try)
                {
                    $count ++;
                }
            }
        }
        elseif( is_numeric($ids) )
        {
            $id = $ids;
            $try = $this->ReportModel->remove($id);
            if ($try)
            {
                $count ++;
            }
        }  
        

        $this->session->set('flashMsg', $count.' deleted record(s)');
        return $this->app->redirect(
            $this->router->url('reports'), 
        );
    }

    public function validateID()
    {
        $urlVars = $this->request->get('urlVars');
        $id = $urlVars ? (int) $urlVars['id'] : [];

        if(empty($id))
        {
            $ids = $this->request->post->get('ids', [], 'array');
            if(count($ids)) return $ids;

            $this->session->set('flashMsg', 'Invalid Report');
            return $this->app->redirect(
                $this->router->url('reports'),
            );
        }

        return $id;
    }
}