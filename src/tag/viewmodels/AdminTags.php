<?php

namespace DTM\tag\viewmodels;

use SPT\Web\Gui\Form;
use SPT\Web\Gui\Listing;
use SPT\Web\ViewModel;

class AdminTags extends ViewModel
{
    public static function register()
    {
        return [
            'layout'=>[
                'backend.tag.list',
                'backend.tag.list.row',
                'backend.tag.list.filter'
            ]
        ];
    }

    public function list()
    {
        $filter = $this->filter()['form'];
        $limit  = $filter->getField('limit')->value;
        $sort   = $filter->getField('sort')->value;
        $search = trim($filter->getField('search')->value);
        $page = $this->state('page', 1, 'int', 'get', 'tag.page');
        if ($page <= 0) $page = 1;
        $method = $this->request->getMethod();
        if ($method == 'POST')
        {
            $page = 1;
            $this->session->set('tag.page', 1);
        }
        
        $where = [];

        if (!empty($search)) {
            $where[] = "(#__tags.name LIKE '%" . $search . "%') OR (#__tags.description LIKE '%" . $search . "%')";
        }

        $start  = ($page - 1) * $limit;
        $sort = $sort ? $sort : '#__tags.name asc';

        $result = $this->TagEntity->list($start, $limit, $where, $sort);
        $total = $this->TagEntity->getListTotal();
        
        if (!$result) {
            $result = [];
            $total = 0;
        }

        foreach ($result as &$item) 
        {
            $tag_tmp = [];
            if ($item['parent_id'])
            {
                $tag_tmp = $this->TagEntity->findByPK($item['parent_id']);
            }

            $item['parent_tag'] = $tag_tmp ? $tag_tmp['name'] : '';
        }

        $limit = $limit == 0 ? $total : $limit;
        $list   = new Listing($result, $total, $limit, $this->getColumns());
        return [
            'list' => $list,
            'page' => $page,
            'start' => $start,
            'sort' => $sort,
            'user_id' => $this->user->get('id'),
            'url' => $this->router->url(),
            'link_search' => $this->router->url('tag/search'),
            'link_list' => $this->router->url('tags'),
            'title_page' => 'Tags',
            'link_form' => $this->router->url('tag'),
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
                'search' => $this->state('search', '', '', 'post', 'tag.search'),
                'limit' => $this->state('limit', 10, 'int', 'post', 'tag.limit'),
                'sort' => $this->state('sort', '', '', 'post', 'tag.sort')
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
                'default' => '#__tags.name asc',
                'options' => [
                    ['text' => 'name ascending', 'value' => '#__tags.name asc'],
                    ['text' => 'name descending', 'value' => '#__tags.name desc'],
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
