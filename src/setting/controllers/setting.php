<?php namespace DTM\setting\controllers;

use SPT\Web\ControllerMVVM;
use SPT\Response;

class setting extends ControllerMVVM
{
    public function form()
    {
        $this->app->set('format', 'html');
        $this->app->set('layout', 'backend.setting.form');
        $this->app->set('page', 'backend');
    }

    public function save()
    {
        $settings = $this->SettingModel->getTypes();
        
        $try = true;
        $data = [];
        foreach($settings as $fields)
        {
            if ($fields)
            {
                foreach($fields as $key => $config)
                {
                    $value = $this->request->post->get($key, '', 'string');
                    $value = (string) $value;
                    $try = $this->OptionModel->set($key, $value);
                    $data[$key] = $value;
                }
            }
        }

        $msg = $try ? 'Update Successfully' : 'Update Fail';
        if (!$try)
        {
            $this->session->set('data_form', $data);
        }
        
        $this->session->set('flashMsg', $msg);

        return $this->app->redirect( $this->router->url('settings'));
    }
}