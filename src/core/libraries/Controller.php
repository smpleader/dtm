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
use SPT\Web\ControllerMVVM as Base;

class Controller extends Base
{
    public function registerViewModels()
    {
        $this->getThemePath();
        $plgName = $this->app->get('currentPlugin', '');
        $this->loadVMFolder(
            SPT_VENDOR_PATH. 'smpleader/dtm/'. $plgName. '/viewmodels',
            '\\DTM\\'. $plgName
        );
    }

    protected function getThemePath()
    {
        if(!defined('SPT_THEME_PATH'))
        {
            $themePath = $this->app->get('themePath', '');
            $theme = $this->app->get('theme', '');
            if( $themePath && $theme )
            {
                $themePath .= '/'. $theme; 
            }
            else
            {
                $themePath = SPT_VENDOR_PATH. 'smpleader/dtm/src/'. $pluginName. '/views';
            }
    
            define('SPT_THEME_PATH', $themePath);
            // Load VMs for theme
            if( is_file(SPT_THEME_PATH.'/_vms.php'))
            { 
                $vmlist = (array) require_once SPT_THEME_PATH.'/_vms.php';
                foreach($vmlist as $key => $item)
                {
                    $path = ''; $namespace = ''; $onlyWidget = true;
                    if(is_string($item))
                    { 
                        $path = SPT_PLUGIN_PATH. '/'. $item. '/viewmodels';
                        $namespace = $this->app->getNamespace(). '\\plugins\\'. $item;
                    }
                    elseif(is_array($item))
                    { 
                        list($path, $namespace, $onlyWidget) = $item; 
                    }

                    $this->loadVMFolder($path, $namespace, $onlyWidget);
                }
            }
        }

        return SPT_THEME_PATH;
    }

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