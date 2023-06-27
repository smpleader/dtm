<?php
namespace DTM\milestone\registers;

use SPT\Application\IApp;
use SPT\Support\Loader;

class Menu
{
    public static function registerItem( IApp $app )
    {
        $container = $app->getContainer();
        $permission = $container->exists('PermissionModel') ? $container->get('PermissionModel') : null;
        $allow_milestone = $permission ? $permission->checkPermission(['milestone_manager', 'milestone_read']) : true;
        $allow_request = $permission ? $permission->checkPermission(['request_manager', 'request_read']) : true;
        $entity = $container->get('MilestoneEntity');
        $router = $container->get('router');
        $path_current = $router->get('actualPath');
        $sitenode = trim($router->get('sitenode'), '/');

        $str = ['detail-request'];
        $check = false;
        foreach ($str as $item)
        {
            if (strpos($path_current, $item) !== false)
            {
                $check = true;
                
            }
        }
        if ($check && $allow_request)
        {
            $request = $container->get('request');
            $version = $container->exists('VersionEntity') ? $container->get('VersionEntity') : null;
            $app->set('menu_type', 'request_menu');
            $urlVars = $request->get('urlVars', []);
            $request_id = (int) $urlVars['request_id'] ?? 0;
            $menu = [];

            $menu = [
                [
                    'link' => $router->url('detail-request/'. $request_id.'#relate_note_link'),
                    'title' => 'Relate Notes',
                    'icon' => '<i class="fa-solid fa-link"></i>',
                    'class' => 'relate-note-popup',
                ],
                [
                    'link' => $router->url('detail-request/'. $request_id),
                    'title' => 'New Note',
                    'icon' => '<i class="fa-solid fa-clipboard"></i>',
                    'class' => 'new-note-popup',
                ],
            ];
            
            return [
                'request_menu' => $menu,
            ];
        }

        $list = $entity->list(0, 0, ['status = 1']);
        $menu = [];
        if ($allow_milestone)
        {
            $active = strpos($path_current, 'milestone');
            $menu = [
                [
                    'link' => $router->url('milestones'),
                    'title' => 'Milestones',
                    'icon' => '<i class="fa-solid fa-business-time"></i>',
                    'class' => $active !== false ? 'active' : '',
                ]
            ];
        }

        if($allow_request)
        {
            foreach($list as $item)
            {
                $title = $item['title'];
                $active = ($sitenode == 'requests') && (strpos($path_current, 'requests/'. $item['id']) !== false) ? 'active' : '';

                $menu[] =  [
                    'link' => $router->url('requests/'. $item['id']),
                    'title' => $title,
                    'icon' => '<i class="me-4 pe-2"></i>',
                    'class' => 'back-ground-sidebar '. $active,
                ];
            }
        }

        return [
            'menu' => $menu,
            'order' => 1,
        ];
    }
}