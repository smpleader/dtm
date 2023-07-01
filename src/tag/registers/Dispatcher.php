<?php
namespace DTM\tag\registers;

use SPT\Application\IApp; 
use SPT\File;

class Dispatcher
{
    public static function dispatch(IApp $app)
    {
        $app->plgLoad('permission', 'CheckSession');

        $cName = $app->get('controller');
        $fName = $app->get('function');
        // prepare note
        $cName = ucfirst($cName);

        $controller = 'DTM\tag\controllers\\'. $cName;
        if(!class_exists($controller))
        {
            $app->raiseError('Invalid controller '. $cName);
        }

        $controller = new $controller($app->getContainer());
        $controller->{$fName}();
        
        

        $fName = 'to'. ucfirst($app->get('format', 'html'));

        $app->finalize(
            $controller->{$fName}()
        );
    }
}