<?php
/**
 * SPT software - homeController
 *
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: Just a basic controller
 *
 */

namespace DTM\media\controllers;

use SPT\Web\ControllerMVVM;

class ajax extends ControllerMVVM
{
    public function upload()
    {
        $data = [
            'file' => $this->request->file->get('file', [], 'array'),
        ];

        $try = $this->MediaModel->add($data);

        $message = $try ? 'Upload Successfully!' : 'Error: '. $this->MediaModel->getError();

        $this->set('message', $message);
        $this->set('status', $try ? 'done' : 'failed');
        $this->app->set('format', 'json');
        return;
    }

    public function list()
    {
        $search = $this->request->post->get('search', '', 'string');
        $page = $this->request->post->get('page', 1, 'int');
        $limit = 20;

        $list = $this->MediaModel->search($search, $page, $limit);

        $this->set('list', $list ? $list : []);
        $this->set('status', 'done');
        $this->app->set('format', 'json');
        return;
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
                if( $this->MediaModel->remove( $id ) )
                {
                    $count++;
                }
            }
        }
        elseif( is_numeric($ids) )
        {
            if( $this->MediaModel->remove($ids ) )
            {
                $count++;
            }
        }  
        

        $this->session->set('flashMsg', $count.' deleted record(s)');
        return $this->app->redirect(
            $this->router->url('admin/media'), 
        );
    }
}