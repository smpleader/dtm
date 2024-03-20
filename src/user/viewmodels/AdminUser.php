<?php
namespace DTM\user\viewmodels; 

use SPT\Web\Gui\Form;
use SPT\Web\Gui\Listing;
use SPT\Web\ViewModel;

class AdminUser extends ViewModel
{
    public static function register()
    {
        return [
            'layout'=>[
                'backend.user.login',
                'backend.user.form',
                'backend.user.profile'
            ]
        ];
    }

    public function login($layoutData, &$viewData)
    {
        $link_google_auth = '';

        return [
            'url' =>  $this->router->url(),
            'link_login' =>  $this->router->url('login'),
            'link_google_auth' =>  $link_google_auth,
        ];
    }

    public function form()
    {
        $urlVars = $this->request->get('urlVars');
        $id = (int) $urlVars['id'];

        $data = $id ? $this->UserEntity->findByPK($id) : [];
        $data_form = $this->session->getform('user', []);
        $this->session->setform('user', []);
        $data = $data_form ? $data_form : $data;

        if ($data && $id)
        {
            $data['password'] = '';
            $groups = $this->UserEntity->getGroups($data['id']);
            foreach ($groups as $group)
            {
                $data['groups'][] = $group['group_id'];
            }
        }
        $form = new Form($this->getFormFields($id), $data);

        return [
           'id' => $id,
           'form' => $form,
           'data' => $data,
           'title_page' => $id ? 'Update User' : 'New User',
           'url' => $this->router->url(),
           'link_list' => $this->router->url('users'),
           'link_form' => $this->router->url('user'),
        ];
    }

    public function getFormFields($id)
    {
        $groups = $this->GroupEntity->list(0, 0, [], 'name asc');
        $options = [];
        foreach ($groups as $group)
        {
            $options[] = [
                'text' => $group['name'],
                'value' => $group['id'],
            ];
        }

        $fields = [
            'id' => ['hidden'],
            'name' => [
                'text',
                'placeholder' => 'Enter Name',
                'showLabel' => false,
                'formClass' => 'form-control',
                'required' => 'required'
            ],
            'username' => ['text',
                'placeholder' => 'Enter User Name',
                'showLabel' => false,
                'formClass' => 'form-control',
                'required' => 'required',
            ],
            'email' => ['email',
                'formClass' => 'form-control',
                'placeholder' => 'Enter Email',
                'showLabel' => false,
                // 'required' => 'required'
            ],
            'password' => ['password',
                'placeholder' => 'Enter Password',
                'showLabel' => false,
                'formClass' => 'form-control'
            ],
            'confirm_password' => ['password',
                'placeholder' => 'Enter Confirm Password',
                'showLabel' => false,
                'formClass' => 'form-control'
            ],
            'status' => ['option',
                'showLabel' => false,
                'type' => 'radio',
                'formClass' => '',
                'default' => 1,
                'options' => [
                    ['text'=>'Active', 'value'=>1],
                    ['text'=>'Inactive', 'value'=>0]
                ]
            ],
            'groups' => ['option',
                'options' => $options,
                'type' => 'checkbox',
                'showLabel' => false,
                'formClass' => 'checkbox-col-3',
            ],
            'token' => ['hidden',
                'default' => $this->token->value(),
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

    public function profile()
    {
        $id = $this->user->get('id');
        $data = $id ? $this->UserEntity->findByPK($id) : [];
        $data_form = $this->session->get('data_form', []);
        $this->session->set('data_form', []);
        $data = $data_form ? $data_form : $data;
        if ($data)
        {
            $data['password'] = '';
            $data['groups'] = [];

            $groups = $this->UserEntity->getGroups($data['id']);
            foreach ($groups as $group)
            {
                $data['groups'][] = $group['group_name'];
            }
            $data['groups'] = implode(', ',  $data['groups']);
        }
        
        $form = new Form($this->getFormFieldsProfile(), $data);

        return [
            'form' => $form,
            'data' => $data,
            'title_page' => 'My Profile',
            'url' => $this->router->url(),
            'link_list' => $this->router->url('profile'),
            'link_form' => $this->router->url('profile'),
        ];
    }

    public function getFormFieldsProfile()
    {
        $fields = [
            'id' => ['hidden'],
            'name' => [
                'text',
                'placeholder' => 'Enter Name',
                'showLabel' => false,
                'formClass' => 'form-control',
                'required' => 'required'
            ],
            'username' => ['hidden',
                'placeholder' => 'Enter User Name',
                'showLabel' => false,
                'disabled' => 'disabled',
                'formClass' => 'form-control readonly',
            ],
            'email' => ['email',
                'formClass' => 'form-control',
                'placeholder' => 'Enter Email',
                'showLabel' => false,
                // 'required' => 'required'
            ],
            'password' => ['password',
                'placeholder' => 'Enter Password',
                'showLabel' => false,
                'formClass' => 'form-control'
            ],
            'confirm_password' => ['password',
                'placeholder' => 'Enter Confirm Password',
                'showLabel' => false,
                'formClass' => 'form-control'
            ],
            'groups' => [
                'type' => 'readonly',
                'showLabel' => false,
            ],
            'token' => ['hidden',
                'default' => $this->token->value(),
            ],
        ];

        return $fields;
    }
}
