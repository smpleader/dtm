<?php
namespace DTM\report_tree\registers;

use SPT\Application\IApp;
use Joomla\DI\Container;

class Dispatcher
{
    public static function dispatch(IApp $app)
    {
        $app->plgLoad('permission', 'CheckSession');
        
        $cName = $app->get('controller');
        $fName = $app->get('function');

        $cName = ucfirst($cName);

        $controller = 'DTM\report_tree\controllers\\'. $cName;
        if(!class_exists($controller))
        {
            $app->raiseError('Invalid controller '. $cName);
        }

        $controller = new $controller($app->getContainer());
        $controller->{$fName}();
        $controller->setCurrentPlugin();
        $controller->useDefaultTheme();

        $fName = 'to'. ucfirst($app->get('format', 'html'));

        $app->finalize(
            $controller->{$fName}()
        );
    }

    private static function registerEntities(Container $container)
    {
        $query = $container->get('query');
        $e = new \DTM\report_tree\entities\TreeStructureEntity($query);
        $e->checkAvailability();
        $container->share( 'TreeStructureEntity', $e, true);

        $e = new \DTM\report_tree\entities\DiagramEntity($query);
        $e->checkAvailability();
        $container->share( 'DiagramEntity', $e, true);

        $container->share( 'TreePhpModel', new \DTM\report_tree\models\TreePhpModel($container), true);
    }
}