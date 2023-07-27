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

class ajax extends ReportController 
{
    public function findrequest()
    {
        $urlVars = $this->request->get('urlVars');
        $id = (int) $urlVars['id'];
       
        $list = $this->TreePhpModel->findRequest($id);
        
        $this->app->set('format', 'json');
        $this->set('status' , 'success');
        $this->set('data' , $list);
        $this->set('message' , '');
        return;
    }
}
