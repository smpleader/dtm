<?php

/**
 * SPT software - ViewModel
 * 
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: A simple View Model
 * 
 */

namespace DTM\tag\viewmodels;

use SPT\Web\ViewModel;
use SPT\View\Gui\Form;

class AdminTag extends ViewModel
{
    public static function register()
    {
        return [
            'layout'=>'backend.tag.form',
        ];
    }
    
    public function form()
    {
        $request = $this->container->get('request');
        $UserEntity = $this->container->get('UserEntity');
        $TagEntity = $this->container->get('TagEntity');
        $router = $this->container->get('router');

        $urlVars = $request->get('urlVars');
        $id = $urlVars ? (int) $urlVars['id'] : 0;

        $data = $id ? $TagEntity->findByPK($id) : [];
        
        $form = new Form($this->getFormFields(), $data);

        return [
            'id' => $id,
            'form' => $form,
            'link_search' => $router->url('tag/search'),
            'data' => $data,
        ];
        
    }

    public function getFormFields()
    {
        $fields = [
            'name' => [
                'text',
                'showLabel' => false,
                'placeholder' => 'Tag Name',
                'formClass' => 'form-control mb-3',
                'required' => 'required',
            ],
            'description' => [
                'textarea',
                'showLabel' => false,
                'placeholder' => 'Description',
                'formClass' => 'form-control',
            ],
            'parent_id' => [
                'option',
                'type' => 'select2',
                'formClass' => 'form-select',
                'options' => [],
                'showLabel' => false,
                'placeholder' => 'Tags',
                'formClass' => 'form-control',
            ],
            'token' => ['hidden',
                'default' => $this->container->get('token')->value(),
            ],
        ];

        return $fields;
    }
}
