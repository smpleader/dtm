<?php

/**
 * SPT software - ViewModel
 * 
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: A simple View Model
 * 
 */

namespace DTM\setting\viewmodels;

use SPT\Web\ViewModel;
use SPT\Web\Gui\Form;

class AdminSetting extends ViewModel
{
    public static function register()
    {
        return [
            'layout'=>'backend.setting.form',
        ];
    }

    public function form()
    {
        $OptionModel = $this->container->get('OptionModel');

        $app = $this->container->get('app');
        $router = $this->container->get('router');
        $SettingModel = $this->container->get('SettingModel');
        $settings = $SettingModel->getTypes();

        $fields = [];
        foreach($settings as $item)
        {
            if (is_array($item))
            {
                $fields = array_merge($fields, $item);
            }
        }

        $data = [];
        foreach ($fields as $key => $value) {
            if ($key != 'token') {
                $data[$key] =  $OptionModel->get($key, '');
            }
        }
        $data_form = $this->session->get('data_form', []);
        $this->session->set('data_form', []);
        $data = $data_form ? $data_form : $data;
        
        $form = new Form($fields, $data);
        $button_header = [
            [
                'link' => '',
                'class' => 'btn btn-outline-success btn_apply',
                'title' => 'Apply',
            ],
            [
                'link' => $router->url('settings'),
                'class' => 'btn ms-2 btn-outline-secondary',
                'title' => 'Cancel',
            ],
        ];
        
        return [
            'fields' => $fields,
            'form' => $form,
            'button_header' => $button_header,
            'settings' => $settings,
            'title_page' => 'Setting',
            'data' => $data,
            'url' => $router->url(),
            'link_form' => $router->url('settings'),
        ];
    }
    
}