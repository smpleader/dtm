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

use SPT\Web\Gui\Form;
use SPT\Web\Gui\Listing;
use SPT\Web\ViewModel;

class AdminRequests extends ViewModel
{
    public static function register()
    {
        return [
            'layout' => [
                'backend.request.list',
                'backend.request.list.row',
                'backend.request.list.filter'
            ]
        ];
    }

    public function list()
    {
        $clear_filter = $this->request->post->get('clear_filter', '', 'string');
        if ($clear_filter)
        {
            $this->session->set('request.filter_tags', []);
        }
        $filter = $this->filter()['form'];
        $urlVars = $this->request->get('urlVars');
        $milestone_id = (int) $urlVars['milestone_id'];

        $limit  = $filter->getField('limit')->value;
        $sort   = $filter->getField('sort')->value;
        $tags   = $filter->getField('filter_tags')->value;
        $search = trim($filter->getField('search')->value);
        $page   = $this->request->get->get('page', 1);
        if ($page <= 0) $page = 1;
        $where = [];
        $where[] = ['milestone_id = '. $milestone_id];

        if( !empty($search) )
        {
            $where[] = "(`title` LIKE '%".$search."%')";
        }
        
        $filter_tags = [];
        if ($tags)
        {
            $filter_tags = [];
            $where_tag = [];

            foreach ($tags as $tag) 
            {
                if ($tag)
                {
                    $tag_tmp = $this->TagEntity->findByPK($tag);
                    if ($tag_tmp)
                    {
                        $filter_tags[] = [
                            'id' => $tag,
                            'name' => $tag_tmp['name'],
                        ];
                    }
    
                    $where_tag[] = 
                    "(`tags` = '" . $tag . "'" .
                    " OR `tags` LIKE '%" . ',' . $tag . "'" .
                    " OR `tags` LIKE '" . $tag . ',' . "%'" .
                    " OR `tags` LIKE '%" . ',' . $tag . ',' . "%' )";
                }
                
            }
            $where_tag = implode(" OR ", $where_tag);

            if ($where_tag)
            {
                $where[] = '('. $where_tag . ')';
            }
        }  
        $start  = ($page-1) * $limit;
        $sort = $sort ? $sort : 'title asc';

        $result = $this->RequestEntity->list( $start, $limit, $where, $sort);
        $total = $this->RequestEntity->getListTotal();
        if (!$result)
        {
            $result = [];
            $total = 0;
            if( !empty($search) )
            {
                $this->session->set('flashMsg', 'Not Found Request');
            }
        }
        $milestone = $this->MilestoneEntity->findByPK($milestone_id);
        $start_date = $milestone['start_date'] && $milestone['start_date'] != '0000-00-00 00:00:00' ? date('d/m/Y', strtotime($milestone['start_date'])) : '';
        $end_date = $milestone['end_date'] && $milestone['end_date'] != '0000-00-00 00:00:00' ? date('d/m/Y', strtotime($milestone['end_date'])) : '';
        $title = $start_date && $end_date ? $milestone['title'] . ' ('. $start_date . ' - '. $end_date .')' : $milestone['title'];

        $title_page = $milestone ? $title .' - Request List' : 'Request List';

        foreach($result as &$item)
        {
            $user_tmp = $this->UserEntity->findByPK($item['created_by']);
            $item['creator'] = $user_tmp ? $user_tmp['name'] : '';
            $tags = $item['tags'] ? explode(',', $item['tags']) : [];
            $tag_tmp = [];
            $item['tags'] = [];
            foreach($tags as $tag)
            {
                $tmp = $this->TagEntity->findByPK($tag);
                if ($tmp)
                {
                    $tag_tmp[] = $tmp['name'];
                    $item['tags'][] = $tmp;
                }
            }
            $item['excerpt_description'] = $this->RequestModel->excerpt($item['description']);
            $item['tag_tmp'] = implode(' , ', $tag_tmp);

            $assigns = $item['assignment'] ? json_decode($item['assignment']) : [];
            $assign_tmp = [];
            $selected_tmp = [];
            foreach($assigns as $assign)
            {
                $user_tmp = $this->UserEntity->findByPK($assign);
                if ($user_tmp)
                {
                    $assign_tmp[] = $user_tmp['name'];
                    $selected_tmp[] = [
                        'id' => $assign,
                        'name' => $user_tmp['name'],
                    ];
                }
            }
            $item['user_assign'] = implode(', ', $assign_tmp);
            $item['assignment'] = json_encode($selected_tmp);
        }

        $version_lastest = $this->VersionEntity->list(0, 1, [], 'created_at desc');
        $version_lastest = $version_lastest ? $version_lastest[0]['version'] : '0.0.0';

        
        $limit = $limit == 0 ? $total : $limit;
        $list   = new Listing($result, $total, $limit, $this->getColumns());

        $permission = $this->container->exists('PermissionModel') ? $this->PermissionModel : null;
        $allow_tag = $permission ? $permission->checkPermission(['tag_manager', 'tag_create']) : true;
        
        return [
            'milestone_id' => $milestone_id,
            'list' => $list,
            'allow_tag' => $allow_tag,
            'version_lastest' => $version_lastest,
            'page' => $page,
            'start' => $start,
            'filter_tags' => json_encode($filter_tags),
            'sort' => $sort,
            'user_id' => $this->user->get('id'),
            'url' => $this->router->url(),
            'link_list' => $this->router->url('requests/'. $milestone_id),
            'link_tag' => $this->router->url('tag/search'),
            'link_user_search' => $this->router->url('request/find-user'),
            'title_page' => $title_page,
            'link_form' => $this->router->url('request/'. $milestone_id),
            'link_detail' => $this->router->url('detail-request'),
            'token' => $this->token->value(),
    
        ];
    }

    public function getColumns()
    {
        return [
            'num' => '#',
            'title' => 'Title',
            'description' => 'Description',
            'col_last' => ' ',
        ];
    }

    protected $_filter;
    public function filter()
    {
        if( null === $this->_filter):
            $data = [
                'search' => $this->state('search', '', '', 'post', 'request.search'),
                'limit' => $this->state('limit', 10, 'int', 'post', 'request.limit'),
                'sort' => $this->state('sort', '', '', 'post', 'request.sort'),
                'filter_tags' => $this->state('filter_tags', [], 'array', 'post', 'request.filter_tags'),
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
            'filter_tags' => [
                'option',
                'type' => 'multiselect',
                'formClass' => 'form-select',
                'options' => [],
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

    public function row($layoutData, $viewData)
    {
        $row = $viewData['list']->getRow();
        return [
            'item' => $row,
            'index' => $viewData['list']->getIndex()
        ];
    }


}
