<?php
namespace DTM\note\registers;

use SPT\Application\IApp;
use SPT\Support\Loader;

class Menu
{
    public static function registerItem( IApp $app )
    {
        $container = $app->getContainer();
        $router = $container->get('router');
        $permission = $container->exists('PermissionModel') ? $container->get('PermissionModel') : null;
        $allow = $permission ? $permission->checkPermission(['note_manager']) : true;
        $path_current = $router->get('actualPath');

        $active = strpos($path_current, 'notes') !== false ? 'active' : '';
        $menu = [];
        if($allow)
        {
            $menu = [
                [
                    'link' => $router->url('notes'),
                    'title' => 'Note Manager',
                    'icon' => '<i class="fa-solid fa-clipboard"></i>',
                    'class' => $active,
                    'childs' => [],
                ],
            ];
        }

        return [
            'menu'=> $menu,
            'order' => 2,
        ];
    }
}