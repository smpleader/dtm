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
            'layouts.message|render',
            'layouts.notification|render',
        ];
    }

    public function render()
    {
        $session = $this->container->get('session');
        $message = $session->get('flashMsg', '');
        $message = is_array($message) ? implode('<br>', $message) : $message;
        $session->set('flashMsg', '');
        return [
            'message' => $message,
        ];
    }
}
