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
        $allow = $permission ? $permission->checkPermission(['report_manager', 'report_read']) : true;
        $path_current = $router->get('actualPath');

        $menu_report = [];
        $app->plgLoad('menu', 'registerReportItem', function ($reports) use (&$menu_report){
            if ($reports && is_array($reports))
            {
                $menu_report = array_merge($menu_report, $reports);
            }
        });

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