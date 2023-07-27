<?php
/**
 * SPT software - homeController
 *
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: Just a basic controller
 *
 */

namespace DTM\report_tree\controllers;

use DTM\report\libraries\ReportController;
use SPT\Web\ControllerMVVM;

class report extends ReportController 
{
    public function newform()
    {
        $this->app->set('layout', 'backend.report.form');
        $this->app->set('page', 'backend');
        $this->app->set('format', 'html');
    }

    public function detail()
    {
        $this->app->set('layout', 'backend.report.form');
        $this->app->set('page', 'backend');
        $this->app->set('format', 'html');
    }

    public function preview()
    {
        $this->app->set('layout', 'backend.note.preview');
        $this->app->set('page', 'backend');
        $this->app->set('format', 'html');
    }

    public function add()
    {
        $data = [
            'title' => $this->request->post->get('title', '', 'string'),
            'structure' => $this->request->post->get('structure', '', 'string'),
            'save_close' => $this->request->post->get('save_close', '', 'string'),
        ];
        $save_close = $this->request->post->get('save_close', '', 'string');
        
        $newId =  $this->TreePhpModel->add($data);
        
        if( !$newId )
        {
            $this->session->set('flashMsg', $this->TreePhpModel->getError());
            return $this->app->redirect(
                $this->router->url('new-report/tree')
            );
        }
        else
        {
            $this->session->set('flashMsg', 'Created Successfully!');
            $link = $save_close ? 'reports' : 'report/detail/'. $newId;
            return $this->app->redirect(
                $this->router->url($link)
            );
        }
    }

    public function update()
    {
        $ids = $this->validateID();

        // TODO valid the request input

        if(is_numeric($ids) && $ids)
        {
            $data = [
                'title' => $this->request->post->get('title', '', 'string'),
                'structure' => $this->request->post->get('structure', '', 'string'),
                'id' => $ids,
                'removes' => $this->request->post->get('removes', '', 'string'),
            ];
            $save_close = $this->request->post->get('save_close', '', 'string');

            $try = $this->TreePhpModel->update($data);
            
            if($try)
            {
                $this->session->set('flashMsg', 'Updated successfully');
                $link = $save_close ? 'reports' : 'report/detail/'. $ids;
                return $this->app->redirect(
                    $this->router->url($link)
                );
            }
            else
            {
                $this->session->set('flashMsg', $this->TreePhpModel->getError());
                return $this->app->redirect(
                    $this->router->url('report/detail/'. $ids)
                );
            }
        }
    }

    public function validateID()
    {
        
        $urlVars = $this->request->get('urlVars');
        $id = (int) $urlVars['id'];

        if(empty($id))
        {
            $ids = $this->request->post->get('ids', [], 'array');
            if(count($ids)) return $ids;

            $this->session->set('flashMsg', 'Invalid report');
            return $this->app->redirect(
                $this->router->url('reports'),
            );
        }

        return $id;
    }
}
