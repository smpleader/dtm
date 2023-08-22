<?php

/**
 * SPT software - ViewModel
 * 
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: A simple View Model
 * 
 */

namespace DTM\media\viewmodels;

use SPT\Web\Gui\Form;
use SPT\Web\Gui\Listing;
use SPT\Web\ViewModel;

class AdminMedia extends ViewModel
{
    public static function register()
    {
        return [
            'layout'=>[
                'backend.list',
                'backend.list.row',
                'backend.list.filter'
            ]
        ];
    }

    public function list()
    {
        $filter = $this->filter()['form'];
        $limit  = $filter->getField('limit')->value;
        $sort   = $filter->getField('sort')->value;
        $search = trim($filter->getField('search')->value);
        $page = $this->state('page', 1, 'int', 'get', 'media.page');
        if ($page <= 0) $page = 1;

        $where = [];

        if (!empty($search)) {
            $where[] = "(`name` LIKE '%" . $search . "%') OR (`note` LIKE '%" . $search . "%')";
        }

        $start  = ($page - 1) * $limit;
        $sort = $sort ? $sort : 'id desc';

        $result = $this->MediaEntity->list($start, $limit, $where, $sort);
        $total = $this->MediaEntity->getListTotal();
        
        if (!$result) {
            $result = [];
            $total = 0;
        }

        $limit = $limit == 0 ? $total : $limit;
        $list   = new Listing($result, $total, $limit, $this->getColumns());
        return [
            'list' => $list,
            'page' => $page,
            'start' => $start,
            'sort' => $sort,
            'url' => $this->router->url(),
            'link_list' => $this->router->url('admin/media'),
            'title_page' => 'Media',
            'link_form' => $this->router->url('admin/media/upload'),
            'token' => $this->token->value(),
        ];
    }

    public function getColumns()
    {
        return [
            'num' => '#',
            'name' => 'Name',
            'description' => 'Description',
            'parent' => 'Parent',
            'col_last' => ' ',
        ];
    }

    protected $_filter;
    public function filter()
    {
        if (null === $this->_filter) :
            $data = [
                'search' => $this->state('search', '', '', 'post', 'media.search'),
                'limit' => $this->state('limit', 18, 'int', 'post', 'media.limit'),
                'sort' => $this->state('sort', '', '', 'post', 'media.sort')
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
                'default' => 18,
                'options' => [
                    ['text' => '18', 'value' => 18],
                    ['text' => '60', 'value' => 60],
                    ['text' => 'All', 'value' => 0],
                ],
                'showLabel' => false
            ],
            'sort' => [
                'option',
                'formClass' => 'form-select',
                'default' => 'id desc',
                'options' => [
                    ['text' => 'id ascending', 'value' => 'id asc'],
                    ['text' => 'id descending', 'value' => 'id desc'],
                    ['text' => 'name ascending', 'value' => 'name asc'],
                    ['text' => 'name descending', 'value' => 'name desc'],
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
