<?php

namespace DTM\setting\registers;

use SPT\Application\IApp;

class Routing
{
    public static function registerEndpoints()
    {
        return [
            'settings'=>[
                'fnc' => [
                    'get' => 'setting.setting.form',
                    'post' => 'setting.setting.save',
                ],
                'permission' => [
                    'get' => ['setting_manager'],
                    'post' => ['setting_manager'],
                ],
            ],
        ];
    }

    public static function afterRouting(IApp $app)
    {
        $container = $app->getContainer();
        if (!$container->exists('OptionModel'))
        {
            return false;
        }

        $OptionModel = $container->get('OptionModel');
        $time_zone = $OptionModel->get('time_zone', '');
        if ($time_zone)
        {
            date_default_timezone_set($time_zone);
        }
        
        return true;
    }
}