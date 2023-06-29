<?php
/**
 * SPT software - Model
 * 
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: Just a basic model
 * 
 */

namespace DTM\setting\models;

use SPT\Container\Client as Base;

class OptionModel extends Base 
{ 
    public function get($type = '', $default_data = '')
    {
        $option = $this->OptionEntity->findOne(['type' => $type]);
        if ($option)
        {
            return $option['data'];
        }

        return $default_data;
    }

    public function set($type, $data)
    {
        $option = $this->OptionEntity->findOne(['type' => $type]); 
        if ($option)
        {
            $try = $this->OptionEntity->update([
                'type' => $type,
                'data' => $data,
                'id' => $option['id'],
            ]);
        }
        else
        {
            $try = $this->OptionEntity->add([
                'type' => $type,
                'data' => $data,
            ]);
        }

        return $try;
    }
}
