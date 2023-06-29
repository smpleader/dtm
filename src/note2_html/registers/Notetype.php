<?php

namespace DTM\note2_html\registers;

use SPT\Application\IApp;

class Notetype
{
    public static function registerType()
    {
        return [
            'html' => [
                'namespace' => 'DTM\note2_html\\',
                'title' => 'Html'
            ]
        ];
    }
}
