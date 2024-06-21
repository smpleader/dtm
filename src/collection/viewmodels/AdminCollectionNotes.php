<?php
namespace DTM\collection\viewmodels;

use SPT\Web\Gui\Form;
use SPT\Web\Gui\Listing;
use SPT\Web\ViewModel;

class AdminCollectionNotes extends ViewModel
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
        $urlVars = $this->request->get('urlVars');
        $filter_name = $urlVars && $urlVars['filter_name'] ? $urlVars['filter_name'] : '';

        $where = [];
        if ($filter_id)
        {
            $collection = $this->CollectionModel->getDetail($filter_id);
        }

        $clear_filter = $this->request->post->get('clear_filter', '', 'string');
        if ($clear_filter)
        {
            $this->session->set('filter_'. $filter_id.'.tags', []);
            $this->session->set('filter_'. $filter_id.'.author', []);
            $this->session->set('filter_'. $filter_id.'.note_type', []);
            if($collection)
            {
                $filters = isset($collection['filters']) ? $collection['filters'] : [];
                foreach($filters as $item)
                {
                    $this->session->set('parent_tag_'. $item. '_'. $filter_id.'.search', []);
                }
            }
        }

        $filter = $this->filter($collection)['form'];
        $limit  = $filter->getField('limit')->value;
        $sort   = $filter->getField('sort')->value;
        $tags   = $filter->getField('tags')->value;
        $note_type   = $filter->getField('note_type')->value;
        $author   = $filter->getField('author')->value;
        $search = trim($filter->getField('search')->value);

        if ($collection)
        {
            $where = array_merge($where, $this->CollectionModel->getFilterWhere($collection, $filter));
        }

        $page = $this->state('page', 1, 'int', 'get', 'filter_'. $filter_id.'.page');
        if ($page <= 0) $page = 1;
        if ($this->request->getMethod() == 'POST')
        {
            $page = 1;
            $this->session->set('filter_'. $filter_id.'.page', 1);
        }

        $title = 'Collection: '. $collection['name'];
        
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
                    $where_tag[] = 'tags LIKE "%('. $tag .')%"';
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
                $where[] = "(#__tags.id IN (" . $item['tags'] . ") )";
                $t2 = $this->TagEntity->list(0, 0, $where, '');
                if ($t2) {
                    foreach ($t2 as $i) {
                        $t1[] = $i['parent_name'] ? $i['parent_name'] .':'.$i['name'] : $i['name'];
                    }
                }
                $data_tags[$item['id']] = implode(', ', $t1);
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
                    'link' => $this->router->url('new-note/'. $type .'?filter='.$filter_name ),
                    'title' => $t['title'] 
                ];
        }
        
        $list   = new Listing($result, $total, $limit, $this->getColumns());
        
        return [
            'list' => $list,
            'types' => $types,
            'data_tags' => $data_tags,
            'page' => $page,
            'filter_id' => $collection['id'],
            'collection' => $collection,
            'start' => $start,
            'filter_tags' => json_encode($filter_tags),
            'sort' => $sort,
            'user_id' => $this->user->get('id'),
            'url' => $this->router->url(),
            'link_list' =>  $this->router->url('collection/'. strtolower($collection['filter_link'])),
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
    public function filter($collection = null)
    {
        $filter_id = $this->app->get('filter_id', '');
        if (null === $this->_filter) :
            $data = [
                'search' => $this->state('search', '', '', 'post', 'filter_'.$filter_id.'.search'),
                'tags' => $this->state('tags', [], 'array', 'post', 'filter_'.$filter_id.'.tags'),
                'note_type' => $this->state('note_type', [], 'array', 'post', 'filter_'.$filter_id.'.note_type'),
                'author' => $this->state('author', [], 'array', 'post', 'filter_'.$filter_id.'.author'),
                'limit' => $this->state('limit', 20, 'int', 'post', 'filter_'.$filter_id.'.limit'),
                'sort' => $this->state('sort', '', '', 'post', 'filter_'.$filter_id.'.sort')
            ];

            if($collection)
            {
                $filters = isset($collection['filters']) ? $collection['filters'] : [];
                foreach($filters as $item)
                {
                    $data['parent_tag_'. $item] = $this->state('parent_tag_'. $item, '', '', 'post', 'parent_tag_'. $item. '_'.$filter_id.'.search');
                }
            }

            $filter = new Form($this->getFilterFields($collection), $data);

            $this->_filter = $filter;
        endif;

        return ['form' => $this->_filter];
    }

    public function getFilterFields($collection = null)
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

        $filter_fields = [
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
                'placeholder' => 'Note Type'
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

        // add filter fields
        if($collection)
        {
            $filters = isset($collection['filters']) ? $collection['filters'] : [];
            foreach($filters as $item)
            {
                $parent = $this->TagEntity->findByPK($item);
                $list_tags = $this->TagEntity->list(0, 0, ['#__tags.parent_id LIKE '. $item]);
                $options = [];
                foreach($list_tags as $tag)
                {
                    $options[] = [
                        'text' => $parent['name'] .':'. $tag['name'],
                        'value' => $tag['id']
                    ];
                }

                $filter_fields['parent_tag_'. $item] = [
                    'option',
                    'type' => 'multiselect',
                    'formClass' => 'form-select',
                    'options' => $options,
                    'showLabel' => false,
                    'placeholder' => $parent['name']
                ];
            }
        }

        return $filter_fields;
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
