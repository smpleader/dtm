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

class AdminFeedback extends ViewModel
{
    public static function register()
    {
        return [
            'layout'=>[
                'backend.feedback.list',
                'backend.feedback.list.row',
                'backend.feedback.list.filter'
            ]
        ];
    }
    
    public function list()
    {
        $request  = $this->container->get('request');
        $TagEntity  = $this->container->get('TagEntity');
        $VersionEntity  = $this->container->get('VersionEntity');
        $NoteEntity  = $this->container->get('NoteEntity');
        $session  = $this->container->get('session');
        $router  = $this->container->get('router');

        $filter = $this->filter()['form'];
        $urlVars = $request->get('urlVars');
        $version_id = (int) $urlVars['version_id'];

        $limit  = $filter->getField('limit')->value;
        $sort   = $filter->getField('sort')->value;
        $search = $filter->getField('search')->value;
        $page   = $request->get->get('page', 1);
        if ($page <= 0) $page = 1;

        $where = [];

        if (!empty($search)) 
        {
            $tags = $TagEntity->list(0, 0, ["`name` LIKE '%". $search ."%' "]);
            $where[] = "(`description` LIKE '%". $search ."%')";
            $where[] = "(`note` LIKE '%". $search ."%')";
            $where[] = "(`title` LIKE '%". $search ."%')";
            if ($tags)
            {
                foreach ($tags as $tag)
                {
                    $where[] = "(`tags` = '" . $tag['id'] . "'" .
                    " OR `tags` LIKE '%" . ',' . $tag['id'] . "'" .
                    " OR `tags` LIKE '" . $tag['id'] . ',' . "%'" .
                    " OR `tags` LIKE '%" . ',' . $tag['id'] . ',' . "%' )";
                }
            }
            $where = [implode(" OR ", $where)];
        }

        $start  = ($page - 1) * $limit;
        $sort = $sort ? $sort : 'title asc';

        $version = $VersionEntity->findByPK($version_id);
        $tag_exist = $this->container->exists('TagEntity');
        $note_exist = $this->container->exists('NoteEntity');
        $result = [];
        if ($tag_exist && $note_exist && $version) {
            
            $tag_feedback = $TagEntity->findOne(["`name` = 'feedback'"]);
            $tag_version = $TagEntity->findOne(["`name` = '". $version['version']."'"]);
            if ($tag_feedback && $tag_version)
            {
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
                $result = $NoteEntity->list($start, $limit, $where, $sort);
                $total = $NoteEntity->getListTotal();
            }
        }
        $data_tags = [];
        foreach ($result as $item){
            if (!empty($item['tags'])){
                $t1 = $where = [];
                $where[] = "(`id` IN (".$item['tags'].") )";
                $t2 = $TagEntity->list(0, 1000, $where,'','`name`');

                foreach ($t2 as $i) {
                    if($i['name'] != $tag_feedback['name'] && $i['name'] != $tag_version['name']) {
                        $t1[] = $i['name'];
                    }
                }
                $data_tags[$item['id']] = implode(', ', $t1);
            }
        }

        if (!$result) {
            $result = [];
            $total = 0;
            $mgs = $search ? 'Feedback not found!' : '';
            $session->set('flashMsg', $mgs);
        }

        

        $list = new Listing($result, $total, $limit, $this->getColumns());
        $version = $version ? $version : ['name' => ''];
        $title_page = $version['name'] ? '<a href="'. $router->url('versions/').'" >Version: '.$version['name'].'</a> >> Feedback ' : 'Feedback';

        return [
            'list' => $list,
            'url' => $router->url(),
            'version_id' => $version_id,
            'data_tags' => $data_tags,
            'link_cancel' => $router->url('versions'),
            'title_page' => $title_page,
            'link_form' => $router->url('note'),
            'token' => $this->container->get('token')->value(),
        ];
    }
    public function getColumns()
    {
        return [
            'num' => '#',
            'title' => 'Title',
            'created_at' => 'Created at',
            'col_last' => ' ',
        ];
    }

    protected $_filter;
    public function filter()
    {
        if (null === $this->_filter) :
            $data = [
                'search' => $this->state('search', '', '', 'post', 'version_feedback.search'),
                'limit' => $this->state('limit', 10, 'int', 'post', 'version_feedback.limit'),
                'sort' => $this->state('sort', '', '', 'post', 'version_feedback.sort')
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
                'default' => 10,
                'options' => [5, 10, 20, 50],
                'showLabel' => false
            ],
            'sort' => [
                'option',
                'formClass' => 'form-select',
                'default' => 'title asc',
                'options' => [
                    ['text' => 'title ascending', 'value' => 'title asc'],
                    ['text' => 'title descending', 'value' => 'title desc'],
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
