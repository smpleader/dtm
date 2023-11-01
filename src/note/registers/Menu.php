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

        $active = strpos($path_current, 'note') !== false ? 'active' : '';
        $menu = [
            [
                'link' => '',
                'title' => 'Notes',
                'icon' => '<i class="fa-solid fa-clipboard"></i>',
                'class' => $active,
                'childs' => [],
            ],
        ];

        $menu[0]['childs'][] = [
            'link' => $router->url('my-notes'),
            'title' => 'My Note', 
            'class' => strpos($path_current, 'my-notes') !== false ? 'active' : '',
        ];

        $menu[0]['childs'][] = [
            'link' => $router->url('share-notes'),
            'title' => 'Shared Note', 
            'class' => strpos($path_current, 'share-notes') !== false ? 'active' : '',
        ];
       
        if($allow)
        {
            $menu[0]['childs'][] = [
                'link' => $router->url('notes'),
                'title' => 'Note Manager', 
                'class' => strpos($path_current, 'notes') !== false && trim($path_current) == '/notes' ? 'active' : '',
            ];
        }

        return [
            'menu'=> $menu,
            'order' => 2,
        ];
    }
}