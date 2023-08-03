<?php
namespace DTM\comment\registers;

use SPT\Application\IApp; 
use SPT\File;

class Dispatcher
{
    public static function dispatch(IApp $app)
    {
        $app->plgLoad('permission', 'CheckSession'); 
        
        // prepare note
        $container = $app->getContainer();
        
        $cName = $app->get('controller');
        $fName = $app->get('function');
        
        $controller = 'DTM\comment\controllers\\'. $cName;
        if(!class_exists($controller))
        {
            $app->raiseError('Invalid controller '. $cName);
        }

        $controller = new $controller($app->getContainer());
        $controller->{$fName}();

        $app->set('theme', $app->cf('adminTheme'));

        $fName = 'to'. ucfirst($app->get('format', 'html'));
        
        $app->finalize(
            $controller->{$fName}()
        );
    }
}