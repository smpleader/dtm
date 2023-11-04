<?php
/**
 * SPT software - Note controller
 *
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: note controller
 *
 */

namespace DTM\note\libraries; 

use SPT\BaseObj;

class Note extends BaseObj
{ 
    private static $_instance;
    public static function api()
    {
        if(null === static::$_instance)
        {
            static::$_instance = new Note();
        }

        return static::$_instance;
    }
    
}