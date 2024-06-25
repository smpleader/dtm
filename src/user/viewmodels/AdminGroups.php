<?php
namespace DTM\user\viewmodels; 

use SPT\Web\Gui\Form;
use SPT\Web\Gui\Listing;
use SPT\Web\ViewModel;

class AdminGroups extends ViewModel
{
    public static function register()
    {
        return [
            'layout'=>[
                'backend.usergroup.list',
                'backend.usergroup.list.row',
                'backend.usergroup.list.filter'
            ]
        ];
    }

    public function list()
    {
        $filter = $this->filter()['form'];

        $limit  = $filter->getField('limit')->value;
        $sort   = $filter->getField('sort')->value;
        $search = trim($filter->getField('search')->value);
        $status = $filter->getField('status')->value;
        $page = $this->state('page', 1, 'int', 'get', 'usergroup.page');

        if ($page <= 0) $page = 1;
        $method = $this->request->getMethod();
        if ($method == 'POST')
        {
            $page = 1;
            $this->session->set('usergroup.page', 1);
        }
        
        $where = [];
        if( !empty($search) )
        {
            $where[] = "(`name` LIKE '%".$search."%' )";
        }

        if(is_numeric($status))
        {
            $where[] = '`status`='. $status;
        }

        $start  = ($page-1) * $limit;
        $sort = $sort ? $sort : 'name ASC';
        $result = $this->GroupEntity->list( $start, $limit, $where, $sort);
        $total = $this->GroupEntity->getListTotal();

        if (!$result)
        {
            $result = [];
            $total = 0;
            if( !empty($search) )
            {
                $this->session->set('flashMsg', 'Groups not found');
            }
        }

        foreach($result as &$group) {
            //get users in group
            $userIn = $this->UserGroupEntity->getUserActive($group['id']);
            $userInGroup = $this->UserGroupEntity->getListTotal();
            $group['user_in'] = $userInGroup;

            //get Right Access
            $group['access'] = (array) json_decode($group['access']);
            $keys = [];
            if ($this->container->exists('PermissionModel'))
            {
                $keys = $this->container->get('PermissionModel')->getAccess();
            }
            
            foreach($group['access'] as $key => $value)
            {
                if (!in_array($value, $keys))
                {
                    unset($group['access'][$key]);
                }
            }
        }
        $limit = $limit == 0 ? $total : $limit;
        $list   = new Listing($result, $total, $limit, $this->getColumns() );

        return [
            'list' => $list,
            'page' => $page,
            'start' => $start,
            'sort' => $sort,
            'user_id' => $this->user->get('id'),
            'url' => $this->router->url(),
            'link_list' => $this->router->url('user-groups'),
            'title_page' => 'User Group Manager',
            'link_form' => $this->router->url('user-group'),
            'token' => $this->token->value(),
        ];
        
    }

    public function getColumns()
    {
        return [
            'num' => '#',
            'name' => 'Name',
            'right_access' => 'Right access',
            'status' => 'Status',
            'col_last' => ' ',
        ];
    }

    protected $_filter;
    public function filter()
    {
        if( null === $this->_filter):
            $data = [
                'search' => $this->state('search', '', '', 'post', 'user-groups.search'),
                'status' => $this->state('status', '','', 'post', 'user-groups.status'),
                'limit' => $this->state('limit', 20, 'int', 'post', 'user-groups.limit'),
                'sort' => $this->state('sort', '', '', 'post', 'user-groups.sort')
            ];

            $filter = new Form($this->getFilterFields(), $data);
            $this->_filter = $filter;
        endif;

        return ['form' => $this->_filter];
    }


    public function getFilterFields()
    {
        return [
            'search' => ['text',
                'default' => '',
                'showLabel' => false,
                'formClass' => 'form-control h-full input_common w_full_475',
                'placeholder' => 'Search..'
            ],
            'status' => ['option',
                'default' => '',
                'formClass' => 'form-select',
                'options' => [
                    ['text' => '--', 'value' => ''],
                    ['text' => 'Blocked', 'value' => '1'],
                    ['text' => 'Active', 'value' => '0']
                ],
                'showLabel' => false
            ],
            'limit' => ['option',
                'formClass' => 'form-select',
                'default' => 20,
                'options' => [
                    ['text' => '20', 'value' => 20],
                    ['text' => '50', 'value' => 50],
                    ['text' => 'All', 'value' => 0],
                ],
                'showLabel' => false
            ],
            'sort' => ['option',
                'formClass' => 'form-select',
                'default' => 'name asc',
                'options' => [
                    ['text' => 'Name ascending', 'value' => 'name asc'],
                    ['text' => 'Name descending', 'value' => 'name desc'],
                    ['text' => 'Status ascending', 'value' => 'status asc'],
                    ['text' => 'Status descending', 'value' => 'status desc'],
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
