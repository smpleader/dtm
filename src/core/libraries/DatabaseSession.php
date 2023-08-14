<?php
/**
 * SPT software - PHP Session
 * 
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: PHP Session
 * 
 */

namespace DTM\core\libraries;

use SPT\Session\DatabaseSession as Base;

class DatabaseSession extends Base
{
    public function getform(string $context, $default = null)
    {
        $key = 'data_form_'. $context;
        return $this->get($key, $default);
    }
    
    public function setform(string $context, $value)
    {
        $key = 'data_form_'. $context;
        return $this->set($key, $value);
    }
}