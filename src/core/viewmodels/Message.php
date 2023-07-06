<?php
/**
 * SPT software - ViewModel
 * 
 * @project: https://github.com/smpleader/spt-boilerplate
 * @author: Pham Minh - smpleader
 * @description: Just a basic viewmodel
 * 
 */
namespace DTM\core\viewmodels; 

use SPT\Web\ViewModel;

class Message extends ViewModel
{
    public static function register()
    {
        return [
            'widgets.message|render',
            'widgets.notification|render',
        ];
    }

    public function render()
    {
        $message = $this->session->get('flashMsg', '');
        $message = is_array($message) ? implode('<br>', $message) : $message;
        $this->session->set('flashMsg', '');
        return [
            'message' => $message,
        ];
    }
}
