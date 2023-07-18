<?php

namespace DTM\note2_presenter\registers;

use SPT\Application\IApp;

class Notetype
{
    public static function registerType()
    {
        return [
            'presenter' => [
                'namespace' => 'DTM\note2_presenter\\',
                'title' => 'Presenter'
            ]
        ];
    }
}
