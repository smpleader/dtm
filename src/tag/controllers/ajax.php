<?php
/**
 * SPT software - homeController
 *
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: Just a basic controller
 *
 */

namespace DTM\tag\controllers;

use SPT\Web\ControllerMVVM;

class ajax extends ControllerMVVM
{
    public function add()
    {
        $data = [
            'name' => $this->request->post->get('name', '', 'string'),
            'description' => $this->request->post->get('description', '', 'string'),
            'parent_id' => $this->request->post->get('parent_id', 0, 'int'),
        ];

        $try = $this->TagModel->add($data);

        $message = $try ? 'Create Successfully!' : 'Error: '. $this->TagModel->getError();

        $this->set('message', $message);
        $this->set('status', $try ? 'done' : 'failed');
        $this->set('id', $try ? $try : 0);
        $this->app->set('format', 'json');
        return;
    }
}