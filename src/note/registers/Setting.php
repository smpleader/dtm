<?php
namespace DTM\note\registers;

use SPT\Application\IApp;
use SPT\Support\Loader;

class Setting
{
    public static function registerItem( IApp $app )
    {
        return[
            'Setting Connections' => [
                'folder_id' => [
                    'text',
                    'label' => 'Folder ID:',
                    'formClass' => 'form-control',
                ],
                'client_id' => [
                    'text',
                    'label' => 'Client ID:',
                    'formClass' => 'form-control',
                ],
                'client_secret' => [
                    'text',
                    'label' => 'Client secret',
                    'formClass' => 'form-control',
                ],
                'access_token' => [
                    'text',
                    'label' => 'Access Token',
                    'formClass' => 'form-control',
                ],
            ]
        ];
    }
}