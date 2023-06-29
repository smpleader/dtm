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

use SPT\Web\ControllerMVVM;

class Treediagram extends ControllerMVVM 
{
    public function detail()
    {
             
        $urlVars = $this->request->get('urlVars');
        $id = (int) $urlVars['id'];

        $exist = $this->DiagramEntity->findByPK($id);

        if(!empty($id) && !$exist)
        {
            $this->session->set('flashMsg', "Invalid note diagram");
            return $this->app->redirect(
                $this->router->url('reports')
            );
        }
        $this->app->set('layout', 'backend.tree_php.form');
        $this->app->set('page', 'backend');
        $this->app->set('format', 'html');
    }

    public function list()
    {
        
        $this->app->set('page', 'backend');
        $this->app->set('format', 'html');
        $this->app->set('layout', 'backend.tree_php.list');
    }

    public function add()
    {
        //check title sprint
        $data = [
            'title' => $this->request->post->get('title', '', 'string'),
            'structure' => $this->request->post->get('structure', '', 'string'),
            'save_close' => $this->request->post->get('save_close', '', 'string'),
        ];
        $save_close = $this->request->post->get('save_close', '', 'string');
        
        $data = $this->TreePhpModel->validate($data);
        if (!$data)
        {
            return $this->app->redirect(
                $this->router->url('tree-php/0')
            );
        }

        // TODO: validate new add
        $newId =  $this->TreePhpModel->add($data);

        if( !$newId )
        {
            $msg = 'Error: Created Failed!';
            $this->session->set('flashMsg', $msg);
            return $this->app->redirect(
                $this->router->url('tree-php/0')
            );
        }
        else
        {
            $this->session->set('flashMsg', 'Created Successfully!');
            $link = $save_close ? 'reports' : 'tree-php/'. $newId;
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

            $data = $this->TreePhpModel->validate($data);
            if (!$data)
            {
                return $this->app->redirect(
                    $this->router->url('tree-php/'. $ids)
                );
            }

            $try = $this->TreePhpModel->update($data);
            
            if($try)
            {
                $this->session->set('flashMsg', 'Updated successfully');
                $link = $save_close ? 'reports' : 'tree-php/'. $ids;
                return $this->app->redirect(
                    $this->router->url($link)
                );
            }
            else
            {
                $msg = 'Error: Updated failed';
                $this->session->set('flashMsg', $msg);
                return $this->app->redirect(
                    $this->router->url('tree-php/'. $ids)
                );
            }
        }
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
                if( $this->TreePhpModel->remove( $id ) )
                {
                    $count++;
                }
            }
        }
        elseif( is_numeric($ids) )
        {
            if( $this->TreePhpModel->remove($ids ) )
            {
                $count++;
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
        $id = (int) $urlVars['id'];

        if(empty($id))
        {
            $ids = $this->request->post->get('ids', [], 'array');
            if(count($ids)) return $ids;

            $this->session->set('flashMsg', 'Invalid note diagram');
            return $this->app->redirect(
                $this->router->url('reports'),
            );
        }

        return $id;
    }
}
