<?php
namespace DTM\shortcut\registers;

use SPT\Application\IApp;
use SPT\Support\Loader;

class Menu
{
    public static function registerItem( IApp $app )
    {
        $container = $app->getContainer();
        $router = $container->get('router');
        $path_current = $router->get('actualPath');
        $ShortcutModel = $container->get('ShortcutModel');

        $shortcuts = $ShortcutModel->getShortcut();
        $menu = [];
        foreach($shortcuts as $shortcut)
        {
            if (isset($shortcut['childs']) && $shortcut['childs'])
            {
                $parent_menu = [];
                if ($shortcut['group'])
                {
                    $parent_menu =  [
                            'link' => '', 
                            'title' => $shortcut['group'], 
                            'icon' => '<i class="fa-solid fa-link"></i>',
                            'class' => '',
                            'childs' => [],
                        ];
                }

                // $active_group = false;
                $child_menu = [];
                foreach($shortcut['childs'] as $item)
                {
                    // Todo: active item menu when open shortcut link
                    // if(trim($path_current, '/') == trim(str_replace($router->url(), '' ,$item['link']), '/'))
                    // {
                    //     $active = 'active';
                    //     $active_group = true;
                    // }
                    // else
                    // {
                    //     $active = '';
                    // }
                    $active = '';

                    $child_menu[] = [
                        'link' => $item['link'], 
                        'title' => $item['name'], 
                        'icon' => '<i class="fa-solid fa-link"></i>',
                        'class' => $active,
                    ];
                }

                if ($parent_menu)
                {
                    $parent_menu['childs'] = $child_menu;
                    $menu[] = $parent_menu;
                }
                else
                {
                    $menu = array_merge($menu, $child_menu);
                }
            }
        }
       
        return [
            'menu'=> $menu,
            'order' => 1,
        ];
    }
}