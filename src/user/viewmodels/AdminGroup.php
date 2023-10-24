<?php
/**
 * SPT software - ViewModel
 * 
 * @project: https://github.com/smpleader/spt-boilerplate
 * @author: Pham Minh - smpleader
 * @description: Just a basic viewmodel
 * 
 */
namespace DTM\user\viewmodels; 

use SPT\Web\Gui\Form;
use SPT\Web\Gui\Listing;
use SPT\Web\ViewModel;

class AdminGroup extends ViewModel
{
    public static function register()
    {
        return [
            'layout'=>'backend.usergroup.form'
        ];
    }

    public function form()
    {
        $request = $this->container->get('request');
        $GroupEntity = $this->container->get('GroupEntity');
        $router = $this->container->get('router');

        $urlVars = $request->get('urlVars');
        $id = (int) $urlVars['id'];

        $data = $id ? $GroupEntity->findByPK($id) : [];
        $data_form = $this->session->getform('usergroup', []);
        $this->session->setform('usergroup', []);
        $data = $data_form ? $data_form : $data;
        
        if (isset($data['access']) && $data['access'])
        {
            $data['access'] = (array) json_decode($data['access']);
        }
        $form = new Form($this->getFormFields($id), $data);

        return [
            'id' => $id,
            'form' => $form,
            'data' => $data,
            'title_page' => $id ? 'Update User Group' : 'New User Group',
            'url' => $router->url(),
            'link_list' => $router->url('user-groups'),
            'link_form' => $router->url('user-group'),
        ];
    }

    public function getFormFields($id)
    {
        $access = [];
        if ($this->container->exists('PermissionModel'))
        {
            $access = $this->container->get('PermissionModel')->getAccessGroup();
        }

        uasort($access, function($a, $b) {return
            (count($a) < count($b)) ? 1 : 0;}
        );
        
        $option = [];
        foreach ($access as $key => $value)
        {
            $option[] = [
                'text' => $key,
                'value' => $value,
            ];
        }

        $fields = [
            'id' => ['hidden'],
            'name' => ['text',
                'showLabel' => false,
                'formClass' => 'form-control',
                'required' => 'required'
            ],
            'description' => ['textarea',
                'formClass' => 'form-control',
                'showLabel' => false,
                'placeholder' => ''
            ],
            'access' => ['option',
                'showLabel' => false,
                'placeholder' => 'Select Right Access',
                'type' => 'checkbox_group',
                'layout' => 'user::fields.checkbox_group',
                'formClass' => 'checkbox-col-3',
                'options' => $option
            ],
            'status' => ['option',
                'type' => 'radio',
                'showLabel' => false,
                'formClass' => '',
                'default' => 1,
                'options' => [
                    ['text'=>'Yes', 'value'=>1],
                    ['text'=>'No', 'value'=>0]
                ]
            ],
            'token' => ['hidden',
                'default' => $this->container->get('token')->value(),
            ],
        ];

        if($id)
        {
            $fields['modified_at'] = ['readonly'];
            $fields['modified_by'] = ['readonly'];
            $fields['created_at'] = ['readonly'];
            $fields['created_by'] = ['readonly'];
        }
        else
        {
            $fields['password']['required'] = 'required';
            $fields['confirm_password']['required'] = 'required';
            $fields['modified_at'] = ['hidden'];
            $fields['modified_by'] = ['hidden'];
            $fields['created_at'] = ['hidden'];
            $fields['created_by'] = ['hidden'];
        }

        return $fields;
    }
}
