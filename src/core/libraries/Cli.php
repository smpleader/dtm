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

use SPT\Application\Cli as Base;
use SPT\Application\Configuration;
use SPT\Application\Token;
use SPT\Application\Plugin\Manager;
use SPT\Web\ViewModelHelper;

class Cli extends Base
{
    public function envLoad()
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
        
        $packages = [];
        foreach(new \DirectoryIterator(SPT_PLUGIN_PATH) as $item) 
        {
            if (!$item->isDot() && $item->isDir())
            {
                $packages[$item->getPathname() .'/'] = $this->namespace. '\\'. $item->getBasename() .'\\'; 
            }
        }
        $packages[SPT_VENDOR_PATH.'smpleader/dtm/src/'] = '\\DTM\\';

        $this->plgManager = new Manager(
            $this,
            $packages
        );

        // setup container
        $this->container->set('app', $this);
        // create request
        $this->request = new Request(); 
        $this->container->set('request', $this->request);
        // access to app config 
        $this->container->set('config', $this->config);

        $this->prepareDb();
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
        }
    }
}