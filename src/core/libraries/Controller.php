<?php
/**
 * SPT software - Controller
 * 
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: Just a core controller
 * 
 */

namespace DTM\core\libraries;

use SPT\Application\IApp;
use SPT\Container\Client;
use SPT\Web\Controller as Core;

class Controller extends Core
{
    protected function getView()
    {
        $pluginName = $this->app->get('currentPlugin', '');

        if(empty($pluginName))
        {
            throw new \Exception('Invalid plugin, can not create content page');
        }

        $this->getThemePath();
        
        return new View(
            $pluginName, 
            new Theme(),
            new ViewComponent($this->app->getRouter()),
            $this->supportMVVM
        );
    }
}