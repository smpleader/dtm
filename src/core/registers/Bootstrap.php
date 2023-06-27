<?php
namespace DTM\core\registers;

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
        $app->set('defaultPlugins', ['milestone', 'note2', 'tag', 'note', 'report', 'setting', 'user', 'version']);

        static::prepareDB($app);
        static::prepareSession($app);
        static::loadBasicClasses($app);
        static::prepareUser($app);
        static::prepareTheme($app);
    }

    private static function prepareDB(IApp $app)
    {
        $container = $app->getContainer();
        $config = $container->get('config');
        try{
            $pdo = new PdoWrapper( $config->db );
            if(!$pdo->connected)
            {
                $tmp = $pdo->getLog();
                throw new \Exception('Connection failed. '. $tmp[1], 500); 
            }

            $container->set('query', new Query( $pdo, ['#__'=>  $config->db['prefix']]));
        } 
        catch(\Exception $e) 
        {
            die( $e->getMessage() );
        }
    }

    private static function prepareSession(IApp $app)
    {
        $container = $app->getContainer();
        $query = $container->get('query');
        $token = $container->get('token');

        $session = new Session(
            $container->exists('query') ? 
            new DatabaseSession( new DatabaseSessionEntity($query), $token->value() ) :
            new PhpSession()
        );

        $container->set('session', $session);
    }

    private static function loadBasicClasses(IApp $app)
    {
        $container = $app->getContainer();
        
        foreach($app->get('defaultPlugins') as $plgName)
        {
            // load entities
            Loader::findClass( 
                SPT_PLUGIN_PATH. '/'. $plgName. '/entities',
                $app->getNamespace().'\\plugins\\'. $plgName. '\entities',
                function($class, $alias) use (&$container)
                {   
                    var_dump($class, $alias);
                    $container->share( $alias, new $class($container->get('query')), true);
                });


            // load models
            Loader::findClass( 
                SPT_PLUGIN_PATH. '/'. $plgName. '/models',
                $app->getNamespace().'\\plugins\\'. $plgName. '\models',
                function($class, $alias) use (&$container)
                {   
                    $container->share( $alias, new $class($container), true);
                });
        }
        //var_dump($container->get('PermissionModel'));
    }

    private static function prepareUser(IApp $app)
    {
        // prepare user
        $container = $app->getContainer();
        $user = new UserInstance( new UserAdapter() );
        $session = $container->get('session');
        $query = $container->get('query');
        $user->init([
            'session' => $session,
            'entity' => new  UserEntity($query)
        ]);
        $container->share('user', $user, true);
    }

    private static function prepareTheme( IApp $app )
    {
        $container = $app->getContainer();
        $config = $container->get('config');
        $request = $container->get('request');

        if(!$config->defaultTheme)
        {
            throw new \Exception('Configuration did not set up theme');
        }
        
        $theme = $request->get('theme', $config->defaultTheme);

        $app->set('theme', $theme);
    }
}