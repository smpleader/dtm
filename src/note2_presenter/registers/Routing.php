<?php

namespace DTM\note2_presenter\registers;

use SPT\Application\IApp;

class Routing
{
    public static function registerEndpoints()
    {
        return [
            'history/note-presenter' => [
                'fnc' => [
                    'get' => 'note2_presenter.history.detail',
                    'post' => 'note2_presenter.history.rollback',
                ],
                'parameters' => ['id'],
            ],
        ];
    }
}
