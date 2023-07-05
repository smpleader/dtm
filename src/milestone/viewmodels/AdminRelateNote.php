<?php

/**
 * SPT software - ViewModel
 * 
 * @project: https://github.com/smpleader/spt-boilerplate
 * @author: Pham Minh - smpleader
 * @description: Just a basic viewmodel
 * 
 */
namespace DTM\milestone\viewmodels; 

use SPT\View\Gui\Form;
use SPT\View\Gui\Listing;
use SPT\Web\ViewModel;

class AdminRelateNote extends ViewModel
{
    public static function register()
    {
        return [
            'layouts.backend.relate_note.form'
        ];
    }
    
    public function form()
    {
        $urlVars = $this->request->get('urlVars');
        $request_id = (int) $urlVars['request_id'];

        $form = new Form($this->getFormFields(), []);

        return [
            'form' => $form,
            'url' => $this->router->url(),
            'link_list' => $this->router->url('relate-notes/'. $request_id),
            'link_form' => $this->router->url('relate-note/'. $request_id),
        ];
    }

    public function getFormFields()
    {
        $notes = $this->NoteEntity->list(0, 0, [], 'title asc');
        
        $options = [];
        foreach ($notes as $note) {
            $options[] = [
                'text' => $note['title'],
                'value' => $note['id'],
            ];
        }
        $fields = [
            'id' => ['hidden'],
            'title' => [
                'text',
                'placeholder' => 'New Relate Note',
                'showLabel' => false,
                'formClass' => 'form-control h-50-px fw-bold rounded-0 fs-3',
                'required' => 'required'
            ],
            'description' => ['textarea',
                'placeholder' => 'Enter Note',
                'showLabel' => false,
                'formClass' => 'form-control rounded-0 border border-1 py-1 fs-4-5',
                'required' => 'required',
            ],
            'note_id' => ['option',
                'options' => $options,
                'type' => 'select2',
                'showLabel' => false,
                'formClass' => 'form-select',
            ],
            'token' => ['hidden',
                'default' => $this->token->value(),
            ],
        ];

        return $fields;
    }
}
