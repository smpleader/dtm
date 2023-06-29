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

class ReportModel extends Base
{
    // Write your code here
    public function getTypes()
    {
        $app = $this->container->get('app');
        $types = [];
        $app->plgLoad('report', 'registerType', function ($type) use (&$types) {
            if (is_array($type) && $type) {
                $types = array_merge($type, $types);
            }
        });

        return $types;
    }

    public function updateStatus($data)
    {
        if (!$data || !is_array($data) || !$data['id']) {
            return false;
        }

        $try = $this->DiagramEntity->update([
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
        $find = $this->DiagramEntity->findByPK($id);
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
            if ($this->DiagramEntity->remove($id)) 
            {
                return true;
            }
        }

        return false;
    }
}
