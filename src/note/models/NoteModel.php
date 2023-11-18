<?php
/**
 * SPT software - Model
 * 
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: Just a basic model
 * 
 */

namespace DTM\note\models;

use SPT\Container\Client as Base;
use SPT\Traits\ErrorString;

class NoteModel extends Base
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

        $try = $this->NoteEntity->remove($id);
        return $try;
    }

    public function searchAjax($search, $ignore, $type)
    {
        $where = [];
        if ($search)
        {
            $where[] = "(`notice` LIKE '%" . $search . "%')";
            $where[] = "(`title` LIKE '%" . $search . "%')";

            $where = ['('. implode(" OR ", $where). ')'];
        }

        if ($type)
        {
            $where[] = "(`type` LIKE '" . $type . "')";
        }

        if ($ignore)
        {
            $where[] = 'id NOT IN('.$ignore.')';
        }
        $where[] = '(status NOT Like -1)';

        $result = $this->NoteEntity->list(0, 0, $where, '`title` asc');
        $result = $result ? $result : [];
        return $result;
    }
}
