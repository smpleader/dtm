<?php
/**
 * SPT software - note controller
 *
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: Just a basic controller
 *
 */

namespace DTM\note2\controllers;

use SPT\Response;
use SPT\Web\ControllerMVVM;

class ajax extends ControllerMVVM
{
    public function types()
    {
        $noteTypes = $this->Note2Model->getTypes();
        $types = [];
        foreach($noteTypes as $type => $t)
        {
            $types[] = [
                    'link' => $this->router->url('new-note2/'. $type ),
                    'title' => $t['title'] 
                ];
        }

        $this->app->set('format', 'json');
        $this->set('status' , 'success');
        $this->set('types' , $types);
        $this->set('message' , '');
        return;
    }
}