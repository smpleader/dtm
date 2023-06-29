<?php
namespace DTM\setting\registers;

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
        $allow = $permission ? $permission->checkPermission(['setting_manager']) : true;

        if (!$allow)
        {
            return false;
        }

        $active = strpos($path_current, 'settings') !== false ? 'active' : '';
        $menu = [
            [
                'link' => $router->url('settings'),
                'title' => 'Settings', 
                'icon' => '<i class="fa-solid fa-gear"></i>',
                'class' => $active,
            ]
        ];

        return [
            'menu' => $menu,
            'order' => 10,
        ];
    }
}