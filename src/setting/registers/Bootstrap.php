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
    }
}