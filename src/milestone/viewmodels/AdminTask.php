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

use SPT\Web\Gui\Form;
use SPT\Web\Gui\Listing;
use SPT\Web\ViewModel;

class AdminTask extends ViewModel
{
    public static function register()
    {
        return [
            'layout'=>'backend.task.form'
        ];
    }

    public function form()
    {
        $urlVars = $this->request->get('urlVars');
        $request_id = (int) $urlVars['request_id'];

        $form = new Form($this->getFormFields(), []);
        $request = $this->RequestEntity->findByPK($request_id);
        $milestone = $request ? $this->MilestoneEntity->findByPK($request['milestone_id']) : ['title' => '', 'id' => 0];

        return [
            'form' => $form,
            'url' => $this->router->url(),
            'link_list' => $this->router->url('tasks/'. $request_id),
            'link_form' => $this->router->url('task/'. $request_id),
        ];
    }

    public function getFormFields()
    {
        $fields = [
            'id' => ['hidden'],
            'title' => [
                'text',
                'placeholder' => 'New Task',
                'showLabel' => false,
                'formClass' => 'form-control h-50-px fw-bold rounded-0 fs-3',
                'required' => 'required'
            ],
            'url' => ['text',
                'placeholder' => 'Enter Url',
                'showLabel' => false,
                'formClass' => 'form-control rounded-0 border border-1 py-1 fs-4-5',
            ],
            'token' => ['hidden',
                'default' => $this->token->value(),
            ],
        ];

        return $fields;
    }
}
