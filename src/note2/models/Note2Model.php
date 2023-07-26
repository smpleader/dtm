<?php
/**
 * SPT software - Model
 * 
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: Just a basic model
 * 
 */

namespace DTM\note2\models;

use SPT\Container\Client as Base;
use SPT\Traits\ErrorString;

class Note2Model extends Base
{ 
    use ErrorString; 

    public function getTypes()
    {
        $noteTypes = $this->app->get('noteTypes', false);
        if(false === $noteTypes)
        {
            $noteTypes = [];
            $this->app->plgLoad('notetype', 'registerType', function($types) use (&$noteTypes) {
                $noteTypes += $types;
            });
    
            $this->app->set('noteTypes', $noteTypes);
        }

        return $noteTypes;
    }

    public function remove($id)
    {
        if (!$id)
        {
            return false;
        }

        $try = $this->Note2Entity->remove($id);
        return $try;
    }

}
