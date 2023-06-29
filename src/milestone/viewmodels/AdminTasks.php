<?php
/**
 * SPT software - ViewModel
 * 
 * @project: https://github.com/smpleader/spt-boilerplate
 * @author: Pham Minh - smpleader
 * @description: Just a basic viewmodel
 * 
 */
namespace DTM\milestone\viewmodels; 

use SPT\View\Gui\Form;
use SPT\View\Gui\Listing;
use SPT\Web\ViewModel;

class AdminTasks extends ViewModel
{
    public static function register()
    {
        return [
            'layouts.backend.task.list',
            'layouts.backend.task.list.filter',
        ];
    }

    public function list()
    {
        $request = $this->container->get('request');
        $router = $this->container->get('router');
        $user = $this->container->get('user');
        $session = $this->container->get('session');
        $TaskEntity = $this->container->get('TaskEntity');
        $RequestEntity = $this->container->get('RequestEntity');
        $MilestoneEntity = $this->container->get('MilestoneEntity');
        $VersionEntity = $this->container->get('VersionEntity');
        $request = $this->container->get('request');

        $filter = $this->filter()['form'];
        $urlVars = $request->get('urlVars');
        $request_id = (int) $urlVars['request_id'];

        $limit  = $filter->getField('limit')->value;
        $sort   = $filter->getField('sort')->value;
        $search = trim($filter->getField('search_task')->value);
        $page   = $request->get->get('page', 1);
        if ($page <= 0) $page = 1;

        $where = [];
        $where[] = ['request_id = '. $request_id];

        if( !empty($search) )
        {
            $where[] = "(`title` LIKE '%".$search."%')";
        }
        
        $start  = ($page-1) * $limit;
        $sort = $sort ? $sort : 'title asc';

        $result = $TaskEntity->list( 0, 0, $where, 0);
        $total = $TaskEntity->getListTotal();
        if (!$result)
        {
            $result = [];
            $total = 0;
        }
        $request = $RequestEntity->findByPK($request_id);
        $milestone = $request ? $MilestoneEntity->findByPK($request['milestone_id']) : ['title' => '', 'id' => 0];
        $title_page = 'Task';

        $version_lastest = $VersionEntity->list(0, 1, [], 'created_at desc');
        $version_lastest = $version_lastest ? $version_lastest[0]['version'] : '0.0.0';
        $tmp_request = $RequestEntity->list(0, 0, ['id = '.$request_id], 0);
        foreach($tmp_request as $item) {
        }
        
        $status = false;

        $list   = new Listing($result, $total, $limit, $this->getColumns() );
        return [
            'request_id' => $request_id,
            'list' => $list,
            'page' => $page,
            'start' => $start,
            'status' => $status,
            'sort' => $sort,
            'user_id' => $user->get('id'),
            'url' => $router->url(),
            'link_list' => $router->url('tasks/'. $request_id),
            'title_page_task' => $title_page,
            'link_form' => $router->url('task/'. $request_id),
            'token' => $this->container->get('token')->value(),
        ];
    }

    public function getColumns()
    {
        return [
            'num' => '#',
            'title' => 'Title',
            'url' => 'url',
            'created_at' => 'Created at',
            'col_last' => ' ',
        ];
    }

    protected $_filter;
    public function filter()
    {
        if( null === $this->_filter):
            $data = [
                'search_task' => $this->state('search', '', '', 'post', 'task.search'),
                'limit' => $this->state('limit', 10, 'int', 'post', 'task.limit'),
                'sort' => $this->state('sort', '', '', 'post', 'task.sort')
            ];

            $filter = new Form($this->getFilterFields(), $data);

            $this->_filter = $filter;
        endif;

        return ['form' => $this->_filter];
    }

    public function getFilterFields()
    {
        return [
            'search_task' => ['text',
                'default' => '',
                'showLabel' => false,
                'formClass' => 'form-control h-full input_common w_full_475',
                'placeholder' => 'Search..'
            ],
            'limit' => ['option',
                'formClass' => 'form-select',
                'default' => 10,
                'options' => [ 5, 10, 20, 50],
                'showLabel' => false
            ],
            'sort' => ['option',
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


}
