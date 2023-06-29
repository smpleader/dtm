<?php
namespace DTM\tag\registers;

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
        $allow = $permission ? $permission->checkPermission(['tag_manager', 'tag_read']) : true;
        if (!$allow)
        {
            return false;
        }

        $active = strpos($path_current, 'tags') !== false ? 'active' : '';
        $menu = [
            [
                'link' => $router->url('tags'),
                'title' => 'Tags', 
                'icon' => '<i class="fa-solid fa-clipboard"></i>',
                'class' => $active,
            ]
        ];
        
        return [
            'menu' => $menu,
            'order' => 3,
        ];
    }
}