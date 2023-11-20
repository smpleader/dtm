<?php

/**
 * SPT software - ViewModel
 * 
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: A simple View Model
 * 
 */

namespace DTM\note\viewmodels;

use SPT\Web\Gui\Form;
use SPT\Web\Gui\Listing;
use SPT\Web\ViewModel;

class AdminNotesTrash extends ViewModel
{
    public static function register()
    {
        return [
            'layout'=>[
                'backend.note.trash',
                'backend.note.trash.row',
                'backend.note.trash.filter'
            ]
        ];
    }

    public function trash()
    {
        $clear_filter = $this->request->post->get('clear_filter', '', 'string');
        if ($clear_filter)
        {
            $this->session->set('note.tags', []);
            $this->session->set('note.author', []);
            $this->session->set('note.note_type', []);
        }
        $filter = $this->filter()['form'];
        $limit  = $filter->getField('limit')->value;
        $sort   = $filter->getField('sort')->value;
        $tags   = $filter->getField('tags')->value;
        $note_type   = $filter->getField('note_type')->value;
        $author   = $filter->getField('author')->value;
        $search = trim($filter->getField('search')->value);
        $mode = $this->app->get('filter', '');

        $page = $this->state('page', 1, 'int', 'get', $mode ? $mode.'_trash.page' : 'note_trash.page');
        if ($page <= 0) $page = 1;
        $method = $this->request->getMethod();
        if ($method == 'POST')
        {
            $page = 1;
            $this->session->set($mode ? $mode.'.page' : 'note.page', 1);
        }

        $where = [];
        $title = 'Trash Note';
        $asset = $this->PermissionModel->getAccessByUser();
        
        if ($mode == 'my-note')
        {
            $where[] = 'created_by = '. $this->user->get('id');
            $author = '';
            $title = 'Trash My Note';
        }
        
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

        $where[] = 'status = -2';
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
            $item['created_at'] = $item['created_at'] && $item['created_at'] != '' ? date('d/m/Y h:i:s', strtotime($item['created_at'])) : '';
            $item['deleted_at'] = $item['deleted_at'] && $item['deleted_at'] != '' ? date('d/m/Y h:i:s', strtotime($item['deleted_at'])) : '';
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
            'asset' => $asset,
            'data_tags' => $data_tags,
            'page' => $page,
            'start' => $start,
            'filter_tags' => json_encode($filter_tags),
            'mode' => $mode,
            'sort' => $sort,
            'user_id' => $this->user->get('id'),
            'url' => $this->router->url(),
            'link_list' =>  $this->router->url( $mode == 'my-note' ? 'my-notes/trash' : 'notes/trash') ,
            'link_back' =>  $this->router->url( $mode == 'my-note' ? 'my-notes' : 'notes') ,
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
        $mode = $this->app->get('filter', '');
        $mode = $mode ? $mode : 'notes';
        if (null === $this->_filter) :
            $data = [
                'search' => $this->state('search', '', '', 'post', $mode. '_trash.search'),
                'tags' => $this->state('tags', [], 'array', 'post', $mode. '_trash.tags'),
                'note_type' => $this->state('note_type', [], 'array', 'post', $mode. '_trash.note_type'),
                'author' => $this->state('author', [], 'array', 'post', $mode. '_trash.author'),
                'limit' => $this->state('limit', 10, 'int', 'post', $mode. '_trash.limit'),
                'sort' => $this->state('sort', '', '', 'post', $mode. '_trash.sort')
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
