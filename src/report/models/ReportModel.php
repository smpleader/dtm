<?php

/**
 * SPT software - Model
 * 
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: Just a basic model
 * 
 */

namespace DTM\report\models;

use SPT\Container\Client as Base;
use SPT\Traits\ErrorString;

class ReportModel extends Base
{
    use ErrorString; 

    public function getTypes()
    {
        $reportTypes = $this->app->get('reportTypes', false);
        if(false === $reportTypes)
        {
            $reportTypes = [];
            $this->app->plgLoad('report', 'registerType', function($types) use (&$reportTypes) {
                $reportTypes += $types;
            });
    
            $this->app->set('reportTypes', $reportTypes);
        }

        return $reportTypes;
    }

    public function updateStatus($data)
    {
        if (!$data || !is_array($data) || !$data['id']) {
            return false;
        }

        $try = $this->ReportEntity->update([
            'id' => $data['id'],
            'status' => $data['status'],
        ]);

        return $try;
    }

    public function remove($id)
    {
        if (!$id) {
            return false;
        }

        $types = $this->getTypes();
        $find = $this->ReportEntity->findByPK($id);
        if ($find) 
        {
            $type = isset($types[$find['report_type']]) ? $types[$find['report_type']] : [];
        }

        if (isset($type['remove_object'])) {
            $remove_object = $this->container->get($type['remove_object']);
        }

        if (is_object($remove_object)) 
        {
            if ($remove_object->remove($id)) 
            {
                return true;
            }
        } 
        else 
        {
            if ($this->ReportEntity->remove($id)) 
            {
                return true;
            }
        }

        return false;
    }
}
