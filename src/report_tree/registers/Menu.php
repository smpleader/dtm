<?php
namespace DTM\report_tree\registers;

use SPT\Application\IApp;
use SPT\Support\Loader;

class Menu
{
    public static function registerReportItem( IApp $app )
    {
        $container = $app->getContainer();
        $router = $container->get('router');
        $path_current = $router->get('actualPath');
        $permission = $container->exists('PermissionModel') ? $container->get('PermissionModel') : null;
        $ReportEntity = $container->get('ReportEntity');
        $allow = $permission ? $permission->checkPermission(['treephp_manager', 'treephp_read']) : true;
        if (!$allow)
        {
            return false;
        }
        
        $list = $ReportEntity->list(0, 0, ['type' => 'tree_php', 'status' => 1]);
        $menu = [];
        foreach($list as $item)
        {
            $active = strpos($path_current, 'tree-php/'. $item['id']) !== false ? 'active' : '';
            $menu[] = [
                'link' => $router->url('tree-php/'. $item['id']),
                'title' => $item['title'], 
                'icon' => '<i class="me-4 pe-2"></i>',
                'class' => 'back-ground-sidebar ' . $active,
            ];
        }
        
        return $menu;
    }
}