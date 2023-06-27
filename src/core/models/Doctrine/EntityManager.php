<?php namespace DTM\core\models\Doctrine;

class EntityManager
{
    private static $ins;
    public static function getInstance() : \Doctrine\ORM\EntityManager
    {
        if (static::$ins === null)
        {
            $paths = array(APP_PATH. 'plugins/orm_pratice/models/Doctrine/entities');
            $config = \Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration(
                $paths,
                false,
                null,
                null,
                false // => fix  BadMethodCallException: SimpleAnnotationReader has been removed in doctrine/annotations 2.
            );

            # set up configuration parameters for doctrine.
            # Make sure you have installed the php7.0-sqlite package.
            $connectionParams = array(
                'driver' => 'pdo_sqlite',
                'path'   => STORAGE_PATH.'db-doctrine.sqlite',
            );

            /*$dbParams = array(
                'driver'         => 'pdo_pgsql',
                'user'           => 'user1',
                'password'       => 'my-awesome-password',
                'host'           => 'postgresql.mydomain.com',
                'port'           => 5432,
                'dbname'         => 'myDbName',
                'charset'        => 'UTF-8',
            );*/

            static::$ins = \Doctrine\ORM\EntityManager::create($connectionParams, $config);
        }

        return static::$ins;
    }
    
    private static $ins2;
    public static function getInstance2() : \Doctrine\ORM\EntityManager
    {
        if (static::$ins2 === null)
        {
            $paths = array(APP_PATH. 'plugins/orm_pratice/models/Doctrine/entities');
            $config = \Doctrine\ORM\Tools\Setup::createAttributeMetadataConfiguration($paths ); 
            $connectionParams = array(
                'driver' => 'pdo_sqlite',
                'path'   => STORAGE_PATH.'db-doctrine.sqlite',
            );
            static::$ins2 = \Doctrine\ORM\EntityManager::create($connectionParams, $config);
        }

        return static::$ins2;
    }
}