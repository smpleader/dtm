<?php

/**
 * SPT software - ViewModel
 * 
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: A simple View Model
 * 
 */

namespace DTM\note2\viewmodels;

use SPT\Web\ViewModel;
use SPT\Web\Gui\Form;

class AdminNoteWidget extends ViewModel
{
    public static function register()
    {
        return [
            'widget'=>[
                'backend.note_modal',
            ]
        ];
    }
    
    public function note_modal()
    {
        $types = $this->Note2Model->getTypes();
        $note_types = [];
        foreach($types as $type => $t)
        {
            $note_types[] = [
                    'link' => $this->router->url('new-note2/'. $type ),
                    'title' => $t['title'] 
                ];
        }

        return [
            'note_types' => $note_types,
        ];
    }
}
