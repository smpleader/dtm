<?php

/**
 * SPT software - ViewModel
 * 
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: A simple View Model
 * 
 */

namespace DTM\note2_html\viewmodels;

use SPT\Web\Gui\Form;
use SPT\Web\Gui\Listing;
use SPT\Web\ViewModel;

class AdminNotes extends ViewModel
{
    public static function register()
    {
        return [
            'layout'=>[
                'backend.note.list',
                'backend.note.list.row',
                'backend.note.list.filter'
            ]
        ];
    }

    public function list()
    {
        $clear_filter = $this->request->post->get('clear_filter', '', 'string');
        if ($clear_filter)
        {
            $this->session->set('note.tags', []);
        }
        $filter = $this->filter()['form'];
        $limit  = $filter->getField('limit')->value;
        $sort   = $filter->getField('sort')->value;
        $tags   = $filter->getField('tags')->value;
        $search = trim($filter->getField('search')->value);
        $page   = $this->request->get->get('page', 1);
        if ($page <= 0) $page = 1;

        $where = [];
        $filter_tags = [];
        
        if (!empty($search) && is_string($search)) {
            $where[] = "(`description` LIKE '%" . $search . "%')";
            $where[] = "(`note` LIKE '%" . $search . "%')";
            $where[] = "(`title` LIKE '%" . $search . "%')";
            $where = [implode(" OR ", $where)];
        }
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

        $start  = ($page - 1) * $limit;
        $sort = $sort ? $sort : 'title asc';

        $result = $this->NoteEntity->list($start, $limit, $where, $sort);
        $total = $this->NoteEntity->getListTotal();
        $data_tags = [];
        
        if (!$result) {
            $result = [];
            $total = 0;
            if (!empty($search)) {
                $this->session->set('flashMsg', 'Notes not found');
            }
        }

        foreach ($result as &$item) {
            if (!empty($item['tags'])) {
                $t1 = $where = [];
                $where[] = "(`id` IN (" . $item['tags'] . ") )";
                $t2 = $this->TagEntity->list(0, 0, $where, '', '`name`');
                if ($t2) {
                    foreach ($t2 as $i) {
                        $t1[] = $i['name'];
                    }
                }
                $data_tags[$item['id']] = implode(',', $t1);
            }

            $item['type'] = $item['type'] ? $item['type'] : 'html';
            $user_tmp = $this->UserEntity->findByPK($item['created_by']);
            $item['created_at'] = $item['created_at'] && $item['created_at'] != '0000-00-00 00:00:00' ? date('d/m/Y', strtotime($item['created_at'])) : '';
            $item['created_by'] = $user_tmp ? $user_tmp['name'] : '';
        }
        $limit = $limit == 0 ? $total : $limit;

        $noteTypes = $this->Note2Model->getTypes();
        $types = [];
        foreach($noteTypes as $type => $t)
        {
            $types[] = [
                    'link' => $this->router->url('new-note2/'. $type ),
                    'title' => $t['title'] 
                ];
        }
        
        $list   = new Listing($result, $total, $limit, $this->getColumns());
        return [
            'list' => $list,
            'types' => $types,
            'data_tags' => $data_tags,
            'page' => $page,
            'start' => $start,
            'filter_tags' => json_encode($filter_tags),
            'sort' => $sort,
            'user_id' => $this->user->get('id'),
            'url' => $this->router->url(),
            'link_list' => $this->router->url('note2'),
            'link_tag' => $this->router->url('tag/search'),
            'title_page' => 'Note Manager',
            'link_form' => $this->router->url('note'),
            'link_preview' => $this->router->url('note2/detail'),
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
                'search' => $this->state('search', '', '', 'post', 'note.search'),
                'tags' => $this->state('tags', [], 'array', 'post', 'note.tags'),
                'limit' => $this->state('limit', 10, 'int', 'post', 'note.limit'),
                'sort' => $this->state('sort', '', '', 'post', 'note.sort')
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
            'tags' => [
                'option',
                'type' => 'multiselect',
                'formClass' => 'form-select',
                'options' => [],
                'showLabel' => false
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
