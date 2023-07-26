<?php
namespace DTM\note2\registers;

use SPT\Application\IApp;
use SPT\Support\Loader;

class Menu
{
    public static function registerItem( IApp $app )
    {
        $container = $app->getContainer();
        $router = $container->get('router');
        $permission = $container->exists('PermissionModel') ? $container->get('PermissionModel') : null;
        $allow = $permission ? $permission->checkPermission(['note_manager', 'note_read']) : true;
        $path_current = $router->get('actualPath');

        if (!$allow)
        {
            return false;
        }

        $active = strpos($path_current, 'note') !== false ? 'active' : '';
        $menu = [
            [
                'link' => $router->url('note2'),
                'title' => 'Notes',
                'icon' => '<i class="fa-solid fa-clipboard"></i>',
                'class' => $active
            ],
        ];
       
        return [
            'menu'=> $menu,
            'order' => 2,
        ];
    }
}