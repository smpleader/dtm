<?php
namespace DTM\version\registers;

use SPT\Application\IApp;
use SPT\Support\Loader;

class Setting
{
    public static function registerItem( IApp $app )
    {
        return [
            'Version' => [
                'version_level' => [
                    'number',
                    'label' => 'Level:',
                    'placeholder' => '',
                    'defaultValue' => 1,
                    'formClass' => 'form-control',
                ],
                'version_level_deep' => [
                    'number',
                    'label' => 'Level Deep:',
                    'placeholder' => '',
                    'defaultValue' => 2,
                    'formClass' => 'form-control',
                ],
            ]
        ];
    }
}