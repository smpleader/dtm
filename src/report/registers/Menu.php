<?php
namespace DTM\report\registers;

use SPT\Application\IApp;
use SPT\Support\Loader;

class Menu
{
    public static function registerItem( IApp $app )
    {
        $container = $app->getContainer();
        $router = $app->getRouter();
        $permission = $container->exists('PermissionModel') ? $container->get('PermissionModel') : null;
        $ReportEntity = $container->get('ReportEntity');
        $allow = $permission ? $permission->checkPermission(['report_manager', 'report_read']) : true;
        $path_current = $router->get('actualPath');

        $menu_report = [];
        $reports = $ReportEntity->list(0, 0, ['status' => 1]);

        $active = strpos($path_current, 'reports') !== false ? 'active' : '';
        $menu = [];
        if($allow)
        {
            $menu = [
                [
                    'link' => $router->url('reports'), 
                    'title' => 'Report', 
                    'icon' => '<i class="fa-solid fa-magnifying-glass-chart"></i>',
                    'class' => $active,
                ],
            ];
        }   
        
        foreach($reports as $item)
        {
            $active = strpos($path_current, 'report/detail/'. $item['id']) !== false ? 'active' : '';
            $menu[] = [
                'link' => $router->url('report/detail/'. $item['id']),
                'title' => $item['title'], 
                'icon' => '<i class="me-4 pe-2"></i>',
                'class' => 'back-ground-sidebar ' . $active,
            ];
        }

        if ($menu_report)
        {
            foreach($menu_report as $item)
            {
                $menu[] = $item;
            }
        }
        
        return [
            'menu' => $menu,
            'order' => 4,
        ];
    }
}