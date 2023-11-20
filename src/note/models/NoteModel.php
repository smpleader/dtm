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

    public function remove($id, $hard_delete = false  )
    {
        if (!$id)
        {
            $this->error = 'Invalid Id Note';
            return false;
        }

        $note = $this->NoteEntity->findByPK($id);
        if (!$note)
        {
            $this->error = 'Invalid Note';
            return false;
        }

        $type = $this->getTypes();
        if (isset($type[$note['type']]['model']) && $type[$note['type']]['model'])
        {
            $container = $this->app->getContainer();
            $model = $type[$note['type']]['model'];

            if ($container->exists($model) && method_exists($this->$model, 'remove'))
            {
                $try = $this->$model->remove($id, $hard_delete);
                if (!$try)
                {
                    $this->error = $this->$model->getError();
                    return false;
                }
            }
        }

        if(!$hard_delete)
        {
            $note['status'] = -2;
            $note['deleted_at'] = date('Y-m-d H:i:s');
            $try = $this->NoteEntity->update($note);
            return $try;
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
        $where[] = '(status > -1)';

        $result = $this->NoteEntity->list(0, 0, $where, '`title` asc');
        $result = $result ? $result : [];
        return $result;
    }
}
