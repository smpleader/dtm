<?php


namespace DTM\milestone\controllers;

use SPT\Web\ControllerMVVM;
use SPT\Response;

class documenthistory extends ControllerMVVM 
{
    public function detail()
    {
        $id = $this->validateID();
        $result = '';
        $document = $this->DocumentHistoryEntity->findByPK($id);
        if ($document)
        {
            $result = $document['description'];
        }

        $this->app->set('format', 'json');
        $this->set('result', $result);
        return ;
    }

    public function rollback()
    {
        $id = $this->validateID();
        $try = $this->DocumentModel-> rollback($id);
        
        $result = $try ? 'ok' : 'failed';
        $message = $try ? 'Update Successfully' : 'Update Failed';
        $description = $try ? $try['description'] : '';
        
        $this->app->set('format', 'json');
        $this->set('result', $result);
        $this->set('message', $message);
        $this->set('description', $description);
        return ;
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
                if( $this->DocumentHistoryEntity->remove( $id ) )
                {
                    $count++;
                }
            }
        }
        elseif( is_numeric($ids) )
        {
            if( $this->DocumentHistoryEntity->remove($ids ) )
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
        $urlVars = $this->request->get('urlVars');
        $id = isset($urlVars['id']) ? (int) $urlVars['id'] : 0;

        if(empty($id))
        {
            $ids = $this->request->post->get('ids', [], 'array');
            if(count($ids)) return $ids;

            $this->session->set('flashMsg', 'Invalid document');
            return $this->app->redirect(
                $this->router->url(),
            );
        }

        return $id;
    }
}