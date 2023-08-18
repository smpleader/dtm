<?php
namespace DTM\media\registers;

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

        $active = strpos($path_current, 'media') !== false ? 'active' : '';
        $menu = [
            [
                'link' => $router->url('admin/media'),
                'title' => 'Media', 
                'icon' => '<i class="fa-solid fa-camera"></i>',
                'class' => $active,
            ]
        ];
        
        return [
            'menu' => $menu,
            'order' => 3,
        ];
    }
}