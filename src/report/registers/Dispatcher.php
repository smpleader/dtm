<?php
namespace DTM\report\registers;

use SPT\Application\IApp;
use SPT\Response;
use DTM\report\libraries\ReportDispatch;

class Dispatcher
{
    public static function dispatch(IApp $app)
    {
        $reportDispatcher = new ReportDispatch($app->getContainer());
        $reportDispatcher->execute();
    }
}