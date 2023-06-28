<?php
/**
 * SPT software - Plugin loader
 * 
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: A plugin supporter
 * @version: 0.8
 * 
 */

namespace DTM\core\libraries;

use SPT\Application\IApp;
use SPT\Log;
use SPT\Support\FncArray;
use \Exception;

class PluginManager extends \SPT\Application\Plugin\Manager
{
    private array $list = [];
    private string $master = '';
    private string $message = '';
    private array $calls = [];
    private IApp $app;

    public function __construct(IApp $app)
    {
        defined('SPT_VENDOR_PATH') or die('Invalid vendor path');

        $this->app = $app;

        $filterActive = false;
        if(is_array($app->cf('activePlugins')))
        {
            $filterActive = $app->cf('activePlugins');
        }

        $this->add(SPT_PLUGIN_PATH, $app->getNamespace(). '\\plugins\\', $filterActive);
        $this->add(SPT_VENDOR_PATH. 'smpleader/dtm/src', '\\DTM\\', $filterActive);
    }

    private function add($path, $namespace, $filterActive)
    {
        foreach(new \DirectoryIterator($path) as $item) 
        {
            if (!$item->isDot() && $item->isDir())
            {
                $plg = $item->getBasename();
                if(is_array($filterActive) && !in_array($plg, $filterActive))
                {
                    continue;
                }

                $namespace = $namespace. $plg. '\\registers';
                $installer = $namespace. '\\Installer';
                $this->list[$plg] = class_exists($installer) ? $installer::info() : [];
                $this->list[$plg]['namespace'] =  $namespace;
            }
        }
    }
}