<?php
namespace DTM\note2\registers;

use SPT\Application\IApp;
use DTM\note2\libraries\NoteDispatch;

class Dispatcher
{
    public static function dispatch(IApp $app)
    {
        $app->plgLoad('permission', 'CheckSession'); 

        $noteDispatcher = new NoteDispatch($app->getContainer());
        $noteDispatcher->execute();
    }
}