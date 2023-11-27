<?php
namespace DTM\filter\viewmodels;

use SPT\Web\Gui\Form;
use SPT\Web\Gui\Listing;
use SPT\Web\ViewModel;

class AdminFilterNotes extends ViewModel
{
    public static function register()
    {
        return [
            'layout'=>[
                'note.list',
                'note.list.row',
                'note.list.filter'
            ]
        ];
    }

    public function list()
    {
        $filter_id = $this->app->get('filter_id', '');
        $clear_filter = $this->request->post->get('clear_filter', '', 'string');
        if ($clear_filter)
        {
            $this->session->set('filter_'. $filter_id.'.tags', []);
            $this->session->set('filter_'. $filter_id.'.author', []);
            $this->session->set('filter_'. $filter_id.'.note_type', []);
        }
        $filter = $this->filter()['form'];
        $limit  = $filter->getField('limit')->value;
        $sort   = $filter->getField('sort')->value;
        $tags   = $filter->getField('tags')->value;
        $note_type   = $filter->getField('note_type')->value;
        $author   = $filter->getField('author')->value;
        $search = trim($filter->getField('search')->value);

        $where = [];
        if ($filter_id)
        {
            
            $filter = $this->FilterModel->getDetail($filter_id);
            if ($filter)
            {
                $where = array_merge($where, $this->FilterModel->getFilterWhere($filter));
            }
        }

        $page = $this->state('page', 1, 'int', 'get', 'filter_'. $filter_id.'.page');
        if ($page <= 0) $page = 1;

        $title = 'Filter: '. $filter['name'];
        
        $filter_tags = [];

        if (!empty($search) && is_string($search)) {
            $where_search = [];
            $where_search[] = "(`data` LIKE '%" . $search . "%')";
            $where_search[] = "(`notice` LIKE '%" . $search . "%')";
            $where_search[] = "(`title` LIKE '%" . $search . "%')";
            $where[] = '('. implode(" OR ", $where_search) .')';
        }
        if ($tags)
        {
            $filter_tags = [];
            $where_tag = [];

            foreach ($tags as $tag) 
            {
                $tag_tmp = $this->TagEntity->findByPK($tag);
                if ($tag_tmp)
                {
                    $filter_tags[] = $tag_tmp;
                }
                if ($tag)
                {
                    $where_tag[] = 'tags LIKE "('. $tag .')"';
                }
                
            }
            $where_tag = implode(" OR ", $where_tag);

            if ($where_tag)
            {
                $where[] = '('. $where_tag . ')';
            }
        } 

        if ($author)
        {
            $author = implode(',', $author);
            $where[] = 'created_by IN ('. $author . ')';
        }

        if ($note_type)
        {
            $note_type = implode('","', $note_type);
            $note_type = '"'. $note_type .'"';
            $where[] = '`type` IN ('. $note_type . ')';
        }

        $where[] = 'status > -1';
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

        $noteTypes = $this->NoteModel->getTypes();
        $types = [];
        foreach($noteTypes as $type => $t)
        {
            $types[] = [
                    'link' => $this->router->url('new-note/'. $type ),
                    'title' => $t['title'] 
                ];
        }
        
        $list   = new Listing($result, $total, $limit, $this->getColumns());
        
        return [
            'list' => $list,
            'types' => $types,
            'data_tags' => $data_tags,
            'page' => $page,
            'filter_id' => $filter['id'],
            'start' => $start,
            'filter_tags' => json_encode($filter_tags),
            'sort' => $sort,
            'user_id' => $this->user->get('id'),
            'url' => $this->router->url(),
            'link_list' =>  $this->router->url('my-filter/'. strtolower($filter['filter_link'])),
            'link_note_trash' => $this->router->url('my-notes/trash'),
            'link_mynote' => $this->router->url('my-notes'),
            'link_tag' => $this->router->url('tag/search'),
            'title_page' => $title,
            'link_form' => $this->router->url('note/edit'),
            'link_preview' => $this->router->url('note/detail'),
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
        $filter_id = $this->app->get('filter_id', '');
        if (null === $this->_filter) :
            $data = [
                'search' => $this->state('search', '', '', 'post', 'filter_'.$filter_id.'.search'),
                'tags' => $this->state('tags', [], 'array', 'post', 'filter_'.$filter_id.'.tags'),
                'note_type' => $this->state('note_type', [], 'array', 'post', 'filter_'.$filter_id.'.note_type'),
                'author' => $this->state('author', [], 'array', 'post', 'filter_'.$filter_id.'.author'),
                'limit' => $this->state('limit', 10, 'int', 'post', 'filter_'.$filter_id.'.limit'),
                'sort' => $this->state('sort', '', '', 'post', 'filter_'.$filter_id.'.sort')
            ];
            $filter = new Form($this->getFilterFields(), $data);

            $this->_filter = $filter;
        endif;

        return ['form' => $this->_filter];
    }

    public function getFilterFields()
    {
        $types = $this->NoteModel->getTypes();
        $options = [];
        foreach($types as $key => $value)
        {
            $options[] = [
                'text' => $value['title'],
                'value' => $key
            ];
        }

        $users = $this->UserEntity->list(0,0);
        $option_users = [];
        foreach($users as $item)
        {
            $option_users[] = [
                'text' => $item['name'],
                'value' => $item['id'],
            ];
        }

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
            'note_type' => [
                'option',
                'type' => 'multiselect',
                'default' => '',
                'formClass' => 'form-select',
                'options' => $options,
                'showLabel' => false,
                'placeholder' => 'Type'
            ],
            'author' => [
                'option',
                'type' => 'multiselect',
                'default' => '',
                'formClass' => 'form-select',
                'options' => $option_users,
                'showLabel' => false,
                'placeholder' => 'Author'
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
