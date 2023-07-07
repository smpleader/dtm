<?php

/**
 * SPT software - ViewModel
 * 
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: A simple View Model
 * 
 */

namespace DTM\report_tree\viewmodels;

use SPT\Web\Gui\Form;
use SPT\Web\Gui\Listing;
use SPT\Web\ViewModel;

class AdminTreePhps extends ViewModel
{
    public static function register()
    {
        return [
            'layout'=>[
                'backend.tree_php.list',
                'backend.tree_php.list.row',
                'backend.tree_php.list.filter'
            ]
        ];
    }

    public function list()
    {
        $request = $this->container->get('request');
        $TagEntity = $this->container->get('TagEntity');
        $DiagramEntity = $this->container->get('DiagramEntity');
        $session = $this->container->get('session');
        $user = $this->container->get('user');
        $router = $this->container->get('router');

        $filter = $this->filter()['form'];
        $limit  = $filter->getField('limit')->value;
        $sort   = $filter->getField('sort')->value;
        $search = $filter->getField('search')->value;
        $page   = $request->get->get('page', 1);
        if ($page <= 0) $page = 1;

        $where = [];

        if (!empty($search) && is_string($search)) {
            $tags = $TagEntity->list(0, 0, ["`name` LIKE '%" . $search . "%' "]);
            $where[] = "(`description` LIKE '%" . $search . "%')";
            $where[] = "(`note` LIKE '%" . $search . "%')";
            $where[] = "(`title` LIKE '%" . $search . "%')";
            if ($tags) {
                foreach ($tags as $tag) {
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

        $result = $DiagramEntity->list($start, $limit, $where, $sort);
        $total = $DiagramEntity->getListTotal();
        $data_tags = [];
        
        if (!$result) {
            $result = [];
            $total = 0;
            if (!empty($search)) {
                $session->set('flashMsg', 'Note Diagram not found');
            }
        }

        foreach ($result as $item) {
            if (!empty($item['tags'])) {
                $t1 = $where = [];
                $where[] = "(`id` IN (" . $item['tags'] . ") )";
                $t2 = $TagEntity->list(0, 0, $where, '', '`name`');
                if ($t2) {
                    foreach ($t2 as $i) {
                        $t1[] = $i['name'];
                    }
                }
                $data_tags[$item['id']] = implode(',', $t1);
            }
        }

        $list   = new Listing($result, $total, $limit, $this->getColumns());
        return [
            'list' => $list,
            'data_tags' => $data_tags,
            'page' => $page,
            'start' => $start,
            'sort' => $sort,
            'user_id' => $user->get('id'),
            'url' => $router->url(),
            'link_list' => $router->url('tree-phps'),
            'title_page' => 'Tree PHP Diagarm',
            'link_form' => $router->url('tree-php'),
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
                'search' => $this->state('search', '', '', 'post', 'tree_php.search'),
                'tags' => $this->state('tags', '', '', 'post', 'tree_php.tags'),
                'limit' => $this->state('limit', 10, 'int', 'post', 'tree_php.limit'),
                'sort' => $this->state('sort', '', '', 'post', 'tree_php.sort')
            ];
            if (strpos($data['search'], ';') == true) {
                $try = explode(';', $data['search']);
                $data['search'] = [];
                $tmp = [];
                foreach ($try as $key => $value) {
                    $tmp[] = $value;
                }
                $data['search'][] = $tmp;
            }
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
            'status' => [
                'option',
                'default' => '1',
                'formClass' => 'form-select',
                'options' => [
                    ['text' => '--', 'value' => ''],
                    ['text' => 'Show', 'value' => '1'],
                    ['text' => 'Hide', 'value' => '0'],
                ],
                'showLabel' => false
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
                    ['text' => 'Title ascending', 'value' => 'title asc'],
                    ['text' => 'Title descending', 'value' => 'title desc'],
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
