<?php
namespace DTM\collection\viewmodels;

use SPT\Web\Gui\Form;
use SPT\Web\Gui\Listing;
use SPT\Web\ViewModel;

class AdminCollections extends ViewModel
{
    public static function register()
    {
        return [
            'layout'=>[
                'collection.list',
                'collection.list.row',
                'collection.list.filter'
            ],
        ];
    }

    public function list()
    {
        $filter = $this->filter()['form'];
        $limit  = $filter->getField('limit')->value;
        $sort   = $filter->getField('sort')->value;
        $search = trim($filter->getField('search')->value);

        $page = $this->state('page', 1, 'int', 'get', 'filter.page');
        if ($page <= 0) $page = 1;
        $method = $this->request->getMethod();
        if ($method == 'POST')
        {
            $page = 1;
            $this->session->set('filter.page', 1);
        }

        $where = [];
        $title = 'My Filters';

        if($search)
        {
            $where[] = 'name  LIKE "%'. $search. '%"';
        }

        $where_shares[] = 'user_id LIKE '. $this->user->get('id');

        $where[] = '('. implode(" OR ", $where_shares). ')';
        
        $start  = ($page - 1) * $limit;
        $sort = $sort ? $sort : 'name asc';
        $result = $this->CollectionEntity->list($start, $limit, $where, $sort);
        $total = $this->CollectionEntity->getListTotal();
        
        if (!$result) {
            $result = [];
            $total = 0;
            if (!empty($search)) {
                $this->session->set('flashMsg', 'Filter not found');
            }
        }

        foreach($result as &$item)
        {
            $item['shared_by'] = '';
            if($item['parent_id'])
            {
                $parent = $this->CollectionEntity->findByPK($item['parent_id']);
                if($parent)
                {
                    $item = $parent;
                    $user = $this->UserEntity->findByPK($parent['user_id']);
                    $item['shared_by'] = $user['name'];
                }
            }
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
            'link_list' =>  $this->router->url('collections'),
            'title_page' => 'Collections',
            'link_form' => $this->router->url('collection/edit'),
            'link_view' => $this->router->url('collection'),
            'token' => $this->token->value(),
        ];
    }

    public function getColumns()
    {
        return [
            'num' => '#',
            'title' => 'Title',
            //            'status' => 'Status',
            'created_at' => 'Created at',
            'col_last' => ' ',
        ];
    }

    protected $_filter;
    public function filter()
    {
        if (null === $this->_filter) :
            $data = [
                'search' => $this->state('search', '', '', 'post', 'filter.search'),
                'limit' => $this->state('limit', 20, 'int', 'post', 'filter.limit'),
                'sort' => $this->state('sort', '', '', 'post', 'filter.sort')
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
                'default' => 'title asc',
                'options' => [
                    ['text' => 'Name ascending', 'value' => 'name asc'],
                    ['text' => 'Name descending', 'value' => 'name desc'],
                    ['text' => 'Created at ascending', 'value' => 'created_at asc'],
                    ['text' => 'Created at descending', 'value' => 'created_at desc'],
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
