<?php
/**
 * SPT software - Report Libraries
 *
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: report libraries
 *
 */

namespace DTM\report\libraries;

use SPT\Application\IApp;
use SPT\Container\Client as Base;
use SPT\Support\Filter;
use DTM\report\libraries\IReportController;

class ReportDispatch extends Base
{
    public function execute()
    {
        $cName = $this->app->get('controller');
        $fName = $this->app->get('function');

        $loadChildPlugin = $this->app->get('loadChildPlugin', false);
        $loadChildPlugin ? $this->childProcess($cName, $fName) : $this->process($cName, $fName);
    }

    private function process($cName, $fName)
    {
        $controller = 'DTM\report\controllers\\'. $cName;
        if(!class_exists($controller))
        {
            $this->app->raiseError('Invalid controller '. $cName);
        }

        $controller = new $controller($this->getContainer());
        $controller->{$fName}();
        
        $this->app->set('theme', $this->app->cf('adminTheme'));

        $fName = 'to'. ucfirst($this->app->get('format', 'html'));

        $this->app->finalize(
            $controller->{$fName}()
        );
    }

    private function childProcess($cName, $fName)
    {
        // prepare report
        $urlVars = $this->app->rq('urlVars');
        $reporttype = '';

        // todo check public_id
        if(isset($urlVars['type']) && !isset($urlVars['id']))
        {
            $reporttype = Filter::cmd($urlVars['type']);
        }
        elseif( isset($urlVars['id']) )
        {
            // TODO: verify report exist
            // TODO: setup report data
            $report = $this->ReportEntity->findByPK($urlVars['id']);
            $reporttype = $report['type']; 
        }

        // to avoid empty reporttype, let set default

        $class = '';

        $reportTypes = $this->ReportModel->getTypes();

        if(empty($reportTypes[$reporttype]) )
        {
            $this->app->raiseError('Invalid Report type '.$reporttype);
        }
        else
        {
            $class = $reportTypes[$reporttype]['namespace'];
        } 

        $this->app->set('reporttype', $reporttype);

        // set plugin info
        $plgName = $this->app->get('mainPlugin');
        $plgName = $plgName['name'].'_'.$reporttype;

        $controller= $class. 'controllers\\'. $cName;
        if(!class_exists($controller))
        {
            $this->app->raiseError('Invalid controller '. $cName);
        }

        $controller = new $controller($this->getContainer());
        
        if(!($controller instanceof IReportController))
        {
            $this->app->raiseError('Prohibited controller '. $cName);
        }
        
        $controller->{$fName}();
        $controller->setCurrentPlugin($plgName);
        $this->app->set('theme', $this->app->cf('adminTheme'));

        $fName = 'to'. ucfirst($this->app->get('format', 'html'));

        $this->app->finalize(
            $controller->{$fName}()
        );
    }
}