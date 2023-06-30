<?php
namespace DTM\user\registers;

use SPT\Application\IApp;
use SPT\Response;

class Dispatcher
{
    public static function dispatch(IApp $app)
    {
        $cName = $app->get('controller');
        $fName = $app->get('function');
        if( $cName != 'user' || !in_array($fName, ['gate', 'login']))
        {
            $app->plgLoad('permission', 'CheckSession');
        }
        
        $cName = ucfirst($cName);
        $controller = 'DTM\user\controllers\\'. $cName;
        if(!class_exists($controller))
        {
            $app->raiseError('Invalid controller '. $cName);
        }

        $controller = new $controller($app->getContainer());
        $controller->{$fName}();
        $controller->setCurrentPlugin();
        $controller->useDefaultTheme();

        $fName = 'to'. ucfirst($app->get('format', 'html')); 

        if(empty( $app->get('theme', '') ))
        {
            $app->set('theme', $app->cf('defaultTheme'));
        }

        $app->finalize(
            $controller->{$fName}()
        );
    }
}