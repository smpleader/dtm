<?php
namespace DTM\note\viewmodels;

use SPT\Web\ViewModel;
use SPT\Web\Gui\Form;

class AdminNoteWidget extends ViewModel
{
    public static function register()
    {
        return [
            'widget'=>[
                'backend.popup_new',
            ]
        ];
    }
    
    public function popup_new()
    {
        $types = $this->NoteModel->getTypes();
        $note_types = [];
        foreach($types as $type => $t)
        {
            $note_types[] = [
                    'link' => $this->router->url('new-note/'. $type ),
                    'title' => $t['title'] 
                ];
        }

        return [
            'note_types' => $note_types,
        ];
    }
}
