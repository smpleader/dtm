<?php

namespace DTM\note2_html\registers;

use SPT\Application\IApp;

class Routing
{
    public static function registerEndpoints()
    {
        return [
            'history/note-html' => [
                'fnc' => [
                    'get' => 'note2_html.history.detail',
                    'post' => 'note2_html.history.rollback',
                ],
                'parameters' => ['id'],
            ],
        ];
    }
}
