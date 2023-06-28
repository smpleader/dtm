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

class SDM extends SPT\Application\Web
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
        $this->plgManager = new \DTM\core\libraries\PluginManager($this);
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
        $this->prepareTheme();
        $this->loadClasses();
    }

    private function prepareDb()
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
            new DatabaseSession( new DatabaseSessionEntity($this->container->query), $this->container->token->value() ) :
            new PhpSession()
        ));
    }

    private function prepareUser()
    {   
        $user = new UserInstance( new UserAdapter() ); 
        $user->init([
            'session' => $this->container->session,
            'entity' => new  UserEntity($this->container->query)
        ]);
        $this->container->share('user', $user, true);
    }

    private  function prepareTheme()
    {
        if(empty($this->config->exdefaultTheme))
        {
            // support to add theme in the controller or dispatcher
            // then, no warning here
        }
        else
        {
            $app->set('theme', $this->config->exdefaultTheme);
        }
    }

    private function loadClasses()
    {
        // TODO: create cache list
        $container = $this->getContainer();
        foreach(new \DirectoryIterator(SPT_PLUGIN_PATH) as $item) 
        {
            if( !$item->isDot() && $item->isDir() )
            {   
                $name =  $item->getBasename();
                // load entities
                Loader::findClass( 
                    SPT_PLUGIN_PATH. '/'. $name. '/entities',
                    $app->getNamespace().'\\plugins\\'. $name. '\entities',
                    function($class, $alias) use (&$container)
                    {   
                        $container->share( $alias, new $class($container->get('query')), true);
                    });
    
    
                // load models
                Loader::findClass( 
                    SPT_PLUGIN_PATH. '/'. $name. '/entities',
                    $app->getNamespace().'\\plugins\\'. $name. '\entities',
                    function($class, $alias) use (&$container)
                    {   
                        $container->share( $alias, new $class($container), true);
                    });
            }
        }

        $dtmPLugins = ['milestone', 'note2', 'tag', 'note', 'report', 'setting', 'user', 'version']; 
        foreach($dtmPLugins as $plgName)
        {
            // load entities
            Loader::findClass( 
                SPT_VENDOR_PATH. 'smpleader/dtm/src/'. $plgName. '/entities',
                '\\DTM\\'. $plgName. '\entities',
                function($class, $alias) use (&$container)
                {   
                    $container->share( $alias, new $class($container->get('query')), true);
                });


            // load models
            Loader::findClass( 
                SPT_VENDOR_PATH. 'smpleader/dtm/src/'. $plgName. '/models',
                '\\DTM\\'. $plgName. '\models',
                function($class, $alias) use (&$container)
                {   
                    $container->share( $alias, new $class($container), true);
                });
        }
    }
}