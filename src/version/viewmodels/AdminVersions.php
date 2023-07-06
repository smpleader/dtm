<?php

/**
 * SPT software - ViewModel
 * 
 * @project: https://github.com/smpleader/spt-boilerplate
 * @author: Pham Minh - smpleader
 * @description: Just a basic viewmodel
 * 
 */

namespace DTM\version\viewmodels;

use SPT\View\Gui\Form;
use SPT\View\Gui\Listing;
use SPT\Web\ViewModel;

class AdminVersions extends ViewModel
{
    public static function register()
    {
        return [
            'layout'=>[
                'backend.version.list',
                'backend.version.list.row',
                'backend.version.list.filter'
            ]
        ];
    }

    public function list()
    {
        $request = $this->container->get('request');
        $session = $this->container->get('session');
        $router = $this->container->get('router');
        $VersionEntity = $this->container->get('VersionEntity');
        $VersionNoteEntity = $this->container->get('VersionNoteEntity');
        $VersionModel = $this->container->get('VersionModel');

        $filter = $this->filter()['form'];

        $limit  = $filter->getField('limit')->value;
        $sort   = $filter->getField('sort')->value;
        $search = trim($filter->getField('search')->value);
        $page   = $request->get->get('page', 1);
        if ($page <= 0) $page = 1;

        $where = [];


        if (!empty($search)) {
            $where[] = "(`name` LIKE '%" . $search . "%')";
            $where[] = "(`version` LIKE '%" . $search . "%')";
            $where[] = "(`description` LIKE '%" . $search . "%')";
            $where = [implode(' OR ', $where)];
        }

        $start  = ($page - 1) * $limit;
        $sort = $sort ? $sort : 'created_at desc';

        $result = $VersionEntity->list($start, $limit, $where, $sort);
        $total = $VersionEntity->getListTotal();

        $get_log = [];
        $get_log = $VersionNoteEntity->list(0, 0, $where, 0);

        if (!$result) {
            $result = [];
            $total = 0;
            $mgs = $search ? 'Version not found!' : '';
            $session->set('flashMsg', $mgs);
        }

        $tag_exist = $this->container->exists('TagEntity');
        $note_exist = $this->container->exists('NoteEntity');
        $TagEntity = $this->container->get('TagEntity');
        $NoteEntity = $this->container->get('NoteEntity');
        $tag_feedback = $TagEntity->findOne(["`name` = 'feedback'"]);

        foreach ($result as &$version) {
            $total_feedback = 0;
            if ($tag_exist && $note_exist) {
                $tag_version = $TagEntity->findOne(["`name` = '" . $version['version'] . "'"]);
                $where = [];
                if ($tag_feedback && $tag_version) {
                    $where = array_merge($where, [
                        "(`tags` = '" . $tag_feedback['id'] . "'" .
                            " OR `tags` LIKE '%" . ',' . $tag_feedback['id'] . "%'" .
                            " OR `tags` LIKE '%" . $tag_feedback['id'] . ',' . "%'" .
                            " OR `tags` LIKE '%" . ',' . $tag_feedback['id'] . ',' . "%' )",
                        "(`tags` = '" . $tag_version['id'] . "'" .
                            " OR `tags` LIKE '%" . ',' . $tag_version['id'] . "%'" .
                            " OR `tags` LIKE '%" . $tag_version['id'] . ',' . "%'" .
                            " OR `tags` LIKE '%" . ',' . $tag_version['id'] . ',' . "%' )"
                    ]);
                    $result_feedback = $NoteEntity->list(0, 0, $where, '');
                    $total_feedback = $NoteEntity->getListTotal();
                }
            }
            if($total_feedback) {
                $version['feedback'] = $total_feedback;
            } else {
                $version['feedback'] = 0;
            }
        }

        $version_number = $VersionModel->getVersion();

        $limit = $limit == 0 ? $total : $limit;
        $list = new Listing($result, $total, $limit, $this->getColumns());
        $user = $this->container->get('user');
        return [
            'list' => $list,
            'page' => $page,
            'start' => $start,
            'sort' => $sort,
            'version_number' => $version_number,
            'get_log' => $get_log,
            'user_id' => $user->get('id'),
            'url' => $router->url(),
            'link_list' => $router->url('versions'),
            'title_page' => 'Version Manager',
            'link_form' => $router->url('version'),
            'link_form_detail' => $router->url('version-notes'),
            'token' => $this->container->get('token')->value(),
        ];
    }

    public function getColumns()
    {
        return [
            'num' => '#',
            'title' => 'Title',
            'release' => 'release',
            'created_at' => 'Created at',
            'col_last' => ' ',
        ];
    }

    protected $_filter;
    public function filter()
    {
        if (null === $this->_filter) :
            $data = [
                'search' => $this->state('search', '', '', 'post', 'version.search'),
                'limit' => $this->state('limit', 20, 'int', 'post', 'version.limit'),
                'sort' => $this->state('sort', '', '', 'post', 'version.sort')
            ];

            $filter = new Form($this->getFilterFields(), $data);
            $this->_filter = $filter;
        endif;

        return ['form' => $this->_filter];
    }

    public function getFilterFields()
    {
        return [
            'search' => [
                'text',
                'default' => '',
                'showLabel' => false,
                'formClass' => 'form-control h-full input_common w_full_475',
                'placeholder' => 'Search..'
            ],
            'limit' => [
                'option',
                'formClass' => 'form-select',
                'default' => 20,
                'options' => [
                    ['text' => '20', 'value' => 20],
                    ['text' => '50', 'value' => 50],
                    ['text' => 'All', 'value' => 0],
                ],
                'showLabel' => false
            ],
            'sort' => [
                'option',
                'formClass' => 'form-select',
                'default' => 'created_at desc',
                'options' => [
                    ['text' => 'Name ascending', 'value' => 'name asc'],
                    ['text' => 'Name descending', 'value' => 'name desc'],
                    ['text' => 'Date ascending', 'value' => 'created_at asc'],
                    ['text' => 'Date descending', 'value' => 'created_at desc'],
                ],
                'showLabel' => false
            ]
        ];
    }

    public function row($layoutData, $viewData)
    {
        $row = $viewData['list']->getRow();
        return [
            'item' => $row,
            'index' => $viewData['list']->getIndex(),
        ];
    }


}
