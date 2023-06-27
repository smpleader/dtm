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
            $this->app->childLoad('notetype', 'registerType', function($types) use (&$noteTypes) {
                $noteTypes = array_merge($types);
            });
    
            $this->app->set('noteTypes', $noteTypes);
        }

        return $noteTypes;
    }

}
