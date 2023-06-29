<?php
/**
 * SPT software - View
 * 
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: Just a core view
 * 
 */

namespace SPT\Web;

use SPT\Web\Theme;
use SPT\Web\ViewLayout;
use SPT\Web\View as Core;

class View extends Core
{
    protected function preparePath(string $name)
    {
        $fullname = str_replace('.', '/', $name);

        $overrides =  $this->noTheme ? [
            // plugin view
            SPT_PLUGIN_PATH. '/'. $this->currentPlugin. '/views/'. $fullname. '.php',
            SPT_PLUGIN_PATH. '/'. $this->currentPlugin. '/views/'. $fullname. '/index.php',
            // default view
            SPT_PLUGIN_PATH. '/core/views/'. $fullname. '.php',
            SPT_PLUGIN_PATH. '/core/views/'. $fullname. '/index.php'

        ] : [
            // theme view for a plugin view
            SPT_THEME_PATH. '/'. $this->currentPlugin. '/'. $fullname. '.php',
            SPT_THEME_PATH. '/'. $this->currentPlugin. '/'. $fullname. '/index.php',
            // theme view for a default
            SPT_THEME_PATH. '/_'. $fullname. '.php',
            SPT_THEME_PATH. '/_'. $fullname. '/index.php',
            // plugin view
            SPT_PLUGIN_PATH. '/'. $this->currentPlugin. '/views/'. $fullname. '.php',
            SPT_PLUGIN_PATH. '/'. $this->currentPlugin. '/views/'. $fullname. '/index.php',
            // default view
            SPT_PLUGIN_PATH. '/core/views/'. $fullname. '.php',
            SPT_PLUGIN_PATH. '/core/views/'. $fullname. '/index.php'
        ];
        
        $this->overrideLayouts[$name] = $overrides;
        $this->paths[$name] = false;
        foreach($overrides as $file)
        {
            if(file_exists($file))
            {
                $this->paths[$name] = $file;
                return;
            }
        }
    }
}