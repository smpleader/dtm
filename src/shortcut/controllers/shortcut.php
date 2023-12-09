<?php
namespace DTM\shortcut\controllers;

use SPT\Response;
use SPT\Web\ControllerMVVM;

class shortcut extends ControllerMVVM
{
    public function list()
    {
        $list = $this->ShortcutModel->getShortcut();

        $this->app->set('format', 'json');
        $this->set('list', $list);
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
                if( $this->ShortcutModel->remove( $id ) )
                {
                    $count++;
                }
            }
        }
        elseif( is_numeric($ids) )
        {
            if( $this->ShortcutModel->remove($ids ) )
            {
                $count++;
            }
        }

        $this->app->set('format', 'json');
        $this->set('status', 'done');
        $this->set('message', $count.' deleted record(s)');
        return ;
    }

    public function validateID()
    {
        $urlVars = $this->request->get('urlVars');
        $id = $urlVars ? (int) $urlVars['id'] : 0;

        if(empty($id))
        {
            $ids = $this->request->post->get('ids', [], 'array');
            if(count($ids)) return $ids;

            $this->session->set('flashMsg', 'Invalid note');
            return $this->app->redirect(
                $this->router->url('my-notes'),
            );
        }

        return $id;
    }

    public function add()
    {
        $try = $this->ShortcutModel->add([
            'name' => $this->request->post->get('name_shortcut', '', 'string'),
            'link' => $this->request->post->get('link_shortcut', '', 'string'),
            'group' => $this->request->post->get('group_shortcut', '', 'string'),
            'user_id' => $this->user->get('id'),
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->user->get('id'),
            'modified_at' => date('Y-m-d H:i:s'),
            'modified_by' => $this->user->get('id'),
        ]);
        
        $status = $try ? 'done' : 'failed';
        $msg = $try ? 'Create Done' : 'Error: '. $this->ShortcutModel->getError();

        $this->app->set('format', 'json');
        $this->set('status', $status);
        $this->set('message', $msg);
        return ;
    }

    public function update()
    {
        $id = $this->validateID();
        $try = $this->ShortcutModel->update([
            'name' => $this->request->post->get('name_shortcut', '', 'string'),
            'link' => $this->request->post->get('link_shortcut', '', 'string'),
            'group' => $this->request->post->get('group_shortcut', '', 'string'),
            'user_id' => $this->user->get('id'),
            'id' => $id,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $this->user->get('id'),
            'modified_at' => date('Y-m-d H:i:s'),
            'modified_by' => $this->user->get('id'),
        ]);
        
        $status = $try ? 'done' : 'failed';
        $msg = $try ? 'Update Done' : 'Error: '. $this->ShortcutModel->getError();
        
        $this->app->set('format', 'json');
        $this->set('status', $status);
        $this->set('message', $msg);
        return ;
    }
}