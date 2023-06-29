<?php
namespace DTM\user\registers;

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

        $allow_user = $permission ? $permission->checkPermission(['user_manager', 'user_read']) : true;
        $allow_usergroup = $permission ? $permission->checkPermission(['usergroup_manager', 'usergroup_read']) : true;
        $allow_profile = $permission ? $permission->checkPermission(['user_manager', 'user_profile']) : true;
        
        $menu_user = [];
        if ($allow_user || $allow_usergroup)
        {
            $active = strpos($path_current, 'user') !== false || strpos($path_current, 'user-group') !== false ? 'active-child' : '';
            $menu_user = [
                'link' => '#',
                'title' => 'Users', 
                'icon' => '<i class="fa-solid fa-users"></i>', 
                'class' => $active,
                'childs' => [],
            ];
        }
        if ($allow_user)
        {
            $active = strpos($path_current, 'user') !== false && strpos($path_current, 'user-group') === false ? 'active' : '';
            $menu_user['childs'][] = [
                'link' => $router->url('users'),
                'title' => 'Users', 
                'icon' => '<i class="fa-solid fa-users"></i>', 
                'class' => $active,
            ];
        }
        if ($allow_usergroup)
        {
            $active = strpos($path_current, 'user-group') !== false ? 'active' : '';
            $menu_user['childs'][] = [
                'link' => $router->url('user-groups'),
                'title' => 'User Groups', 
                'icon' => '<i class="fa-solid fa-user-group"></i>', 
                'class' => $active,
            ];
        }

        if ($menu_user)
        {
            $menu[] = $menu_user;
        }

        if ($allow_profile)
        {
            $active = strpos($path_current, 'profile') !== false ? 'active' : '';
            $menu[] = [
                'link' => $router->url('profile'),
                'title' => 'Profile', 
                'icon' => '<i class="fa-solid fa-user"></i>', 
                'class' => $active,
            ];
        }
        
        $menu[] = [
            'link' => $router->url('logout'),
            'title' => 'Logout', 
            'icon' => '<i class="fa-solid fa-right-from-bracket"></i>', 
            'class' => '',
        ];

        return [
            'menu' => $menu,
            'order' => 11,
        ];
    }
}