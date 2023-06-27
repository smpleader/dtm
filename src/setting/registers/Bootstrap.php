<?php
namespace DTM\setting\registers;

use SPT\Query;
use SPT\Response;
use SPT\Application\IApp;
use SPT\Support\Loader;
use SPT\Extend\Pdo as PdoWrapper;
use SPT\Session\Instance as Session;
use SPT\Session\PhpSession;
use SPT\Session\DatabaseSession;
use SPT\Session\DatabaseSessionEntity;
use SPT\User\Instance as UserInstance;
use SPT\User\SPT\User as UserAdapter;
use DTM\user\entities\UserEntity;

class Bootstrap
{
    public static function initialize( IApp $app)
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