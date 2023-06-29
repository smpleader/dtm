<?php
namespace DTM\version\registers;

use SPT\Application\IApp;
use SPT\Support\Loader;

class Menu
{
    public static function registerItem( IApp $app )
    {
        $container = $app->getContainer();
        $router = $container->get('router');
        $path_current = $router->get('actualPath');

        $permission = $container->exists('PermissionModel') ? $container->get('PermissionModel') : null;
        $allow = $permission ? $permission->checkPermission(['version_manager', 'version_read']) : true;
        if (!$allow)
        {
            return false;
        }

        $active = strpos($path_current, 'version') !== false ? 'active' : '';
        $menu = [
            [
                'link' => $router->url('versions'),
                'title' => 'Versions', 
                'icon' => '<i class="fa-solid fa-code-branch"></i>',
                'class' => $active,
            ]
        ];
        

        return [
            'menu' => $menu,
            'order' => 5,
        ];
    }
}