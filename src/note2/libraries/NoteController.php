<?php
/**
 * SPT software - Note controller
 *
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: note controller
 *
 */

namespace DTM\note2\libraries;

use SPT\Web\ControllerMVVM;
use SPT\Web\ViewModelHelper;
use SPT\Web\View;
use SPT\Web\ViewComponent;
use SPT\Web\Theme;

class NoteController extends ControllerMVVM implements INoteController
{
    protected function getView()
    {
        $pluginName = $this->app->get('currentPlugin', '');
        $noteType = $this->app->get('noteType', ''); 

        if(empty($pluginName) || empty($noteType))
        {
            throw new \Exception('Invalid plugin, can not create content page');
        }

        $themePath = $this->app->get('themePath', '');
        $theme = $this->app->get('theme', '');
        if( $themePath && $theme )
        {
            $themePath .= '/'. $theme; 
        }
        else
        {
            $themePath = SPT_PLUGIN_PATH. '/'. $pluginName. '_'. $noteType. '/views';
        }

        $this->getThemePath();
        
        return new View(
            $pluginName. '_'. $noteType, 
            new Theme($themePath),
            new ViewComponent($this->app->getRouter()),
            $this->supportMVVM
        );
    }

    public function registerViewModels()
    {
        $this->getThemePath();
        // load vm from child plugin
        $plgName = $this->app->get('currentPlugin', ''). '_'. $this->app->get('noteType', ''); 

        $vmFolder = SPT_PLUGIN_PATH. '/'. $plgName. '/viewmodels';
                
        if( is_dir($vmFolder))
        {
            $this->loadVMFolder($vmFolder, $this->app->getNamespace(). '\\plugins\\'. $plgName);
        }
    }

    // form to create new
    function newform(){}
    
    // save new 
    function add(){}

    // update existing 
    function update(){}

    // remove existing(s)
    function delete(){}

    // list record by filter(s)
    //function list(){}
}