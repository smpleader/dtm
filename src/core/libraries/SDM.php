<?php
/**
 * SPT software - SDM Application
 * 
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: A web application based Joomla container
 * @version: 0.8
 * 
 */

namespace DTM\core\libraries;
 
use SPT\Router\ArrayEndpoint as Router;
use SPT\Request\Base as Request;
use SPT\Response; 
use SPT\Query;
use SPT\Support\Loader;
use SPT\Extend\Pdo as PdoWrapper;
use SPT\Session\Instance as Session;
use SPT\Session\PhpSession;
use SPT\Session\DatabaseSession;
use SPT\Session\DatabaseSessionEntity;
use SPT\User\Instance as UserInstance;
use SPT\User\SPT\User as UserAdapter;
use DTM\user\entities\UserEntity;

use SPT\Application\Web as Base;
use SPT\Application\Configuration;
use SPT\Application\Token;
use SPT\Application\Plugin\Manager;
use SPT\Web\ViewModelHelper;

class SDM extends Base
{
    protected function envLoad()
    {   
        if(!defined('SPT_VENDOR_PATH'))
        {
            if(file_exists(SPT_PUBLIC_PATH. '../vendor'))
            {
                define('SPT_VENDOR_PATH', SPT_PUBLIC_PATH. '../vendor/');
            }
            else
            {
                die('SPT_VENDOR_PATH not set');
            }
        }
        
        $this->config = new Configuration(null);
        $this->plgManager = new Manager(
            $this,
            [   SPT_PLUGIN_PATH => $this->namespace. '\\plugins\\',
                SPT_VENDOR_PATH.'smpleader/dtm/src/' => '\\DTM\\'
            ]
        );
        // setup container
        $this->container->set('app', $this);
        // create request
        $this->request = new Request(); 
        $this->container->set('request', $this->request);
        // create router
        $this->router = new Router($this->config->subpath, '');
        $this->container->set('router', $this->router);
        // access to app config 
        $this->container->set('config', $this->config);
        // token
        $this->container->set('token', new Token($this->config, $this->request));

        $this->prepareDb();
        $this->prepareSession();
        $this->prepareUser();
        $this->loadClasses();
    }

    protected function prepareDb()
    {
        try{
            $pdo = new PdoWrapper( $this->config->db );
            if(!$pdo->connected)
            {
                throw new \Exception('Connection failed.', 500); 
            }

            $this->container->set('query', new Query( $pdo, ['#__'=>  $this->config->db['prefix']]));
        } 
        catch(\Exception $e) 
        {
            $this->raiseError( $e->getMessage() );
        }
    }

    private function prepareSession()
    {
        $this->container->set('session', new Session(
            $this->container->exists('query') ? 
            new DatabaseSession( new DatabaseSessionEntity($this->container->get('query')), $this->container->get('token')->value() ) :
            new PhpSession()
        ));
    }

    private function prepareUser()
    {   
        $user = new UserInstance( new UserAdapter() ); 
        $user->init([
            'session' => $this->container->get('session'),
            'entity' => new  UserEntity($this->container->get('query'))
        ]);
        $this->container->share('user', $user, true);
    }

    private function loadClasses()
    {
        // TODO: create cache list
        $container = $this->getContainer();
        foreach($this->plgManager->getList() as $plg)
        {
            Loader::findClass( 
                $plg['path']. '/entities', 
                $plg['namespace']. '\entities', 
                function($classname, $fullname) use (&$container)
                {
                    $x = new $fullname($container->get('query'));
                    //$x->checkAvailability();
                    $container->share( $classname, $x, true);
                });


            // load models
            Loader::findClass( 
                $plg['path']. '/models', 
                $plg['namespace']. '\models', 
                function($classname, $fullname) use (&$container)
                {
                    $container->share( $classname, new $fullname($container), true);
                });

            // load viewmodels of widgets
            Loader::findClass( 
                $plg['path']. '/viewmodels', 
                $plg['namespace']. '\viewmodels', 
                function($classname, $fullname) use (&$container, $plg)
                {
                    $classname .= 'VM';
                    $this->vmClasses[$plg['name']][] = [$classname, $fullname];

                    $registers = $fullname::register();
                    if(isset($registers['widget']))
                    {
                        $widgetLayouts = false;

                        if(is_array($registers['widget']))
                        {
                            $widgetLayouts = [];
                            foreach($registers['widget'] as $layout)
                            {
                                $widgetLayouts[] = $plg['name']. '::'. $layout;
                            }
                        }
                        elseif(is_string($registers['widget']))
                        {
                            $widgetLayouts = $plg['name']. '::'. $registers['widget'];
                        }

                        if($widgetLayouts)
                        {
                            $container->share( $classname, new $fullname($container), true);
                            ViewModelHelper::prepareVM(
                                'widget',
                                $classname, 
                                $widgetLayouts, 
                                $container
                            );
                        }
                    }
                });
        }
    }

    protected function routing()
    {
        // TODO: load cache
        // TODO: load table
        $router = $this->router;
        $this->plgManager->call('all')->run('routing', 'registerEndpoints', false, function ($endpoints) use ($router){
            $router->import($endpoints);
        });

        $this->plgManager->call('all')->run('routing', 'afterRouting');
    }
}