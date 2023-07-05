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

class AdminRelateNotes extends ViewModel
{   
    public static function register()
    {
        return [
            'layouts.backend.relate_note.list',
            'layouts.backend.relate_note.list.filter',
            'layouts.backend.relate_note.list.javascript',
        ];
    
    }

    public function list()
    {
        $filter = $this->filter()['form'];
        $urlVars = $this->request->get('urlVars');
        $request_id = (int) $urlVars['request_id'];

        $limit  = $filter->getField('limit')->value;
        $sort   = $filter->getField('sort')->value;
        $search = trim($filter->getField('search')->value);
        $page   = $this->request->get->get('page', 1);
        if ($page <= 0) $page = 1;

        $where = [];
        $where[] = ['request_id = '. $request_id];

        if( !empty($search) )
        {
            $where[] = "(`title` LIKE '%".$search."%')";
        }
        
        $start  = ($page-1) * $limit;
        $sort = $sort ? $sort : 'title asc';

        $result = $this->RelateNoteEntity->list( 0, 0, $where, 0);
        $total = $this->RelateNoteEntity->getListTotal();
        if (!$result)
        {
            $result = [];
            $total = 0;
        }
        $request = $this->RequestEntity->findByPK($request_id);
        $milestone = $request ? $this->MilestoneEntity->findByPK($request['milestone_id']) : ['title' => '', 'id' => 0];
        $title_page_relate_note = 'Related Notes';

        $note_exist = $this->container->exists('NoteEntity');

        $notes = [];
        foreach ($result as $index => &$item)
        {
            $note_tmp = false;
            if ($note_exist)
            {
                $note_tmp = $this->NoteEntity->findByPK($item['note_id']);
                if ($note_tmp)
                {
                    $item['title'] = $note_tmp['title'];
                    $item['type'] = $note_tmp['type'];
                    $item['description'] = strip_tags((string) $note_tmp['description']) ;
                    $item['tags'] = $note_tmp['tags'] ;
                }
                else
                {
                    unset($result[$index]);
                }

                if (!empty($item['tags'])){
                    $t1 = $where = [];
                    $where[] = "(`id` IN (".$item['tags'].") )";
                    $t2 = $this->TagEntity->list(0, 1000, $where,'','`name`');
    
                    foreach ($t2 as $i) $t1[] = $i['name'];
    
                    $item['tags'] = implode(', ', $t1);
                }
            }

            if (in_array($item['type'], ['sheetjs', 'presenter']))
            {
                $item['description'] = '';
            }

            $item['description'] = $this->RequestModel->excerpt($item['description']);

            if ($note_tmp)
            {
                $notes[] = $item;
            }
        }

        $result = $notes;
        $version_lastest = $this->VersionEntity->list(0, 1, [], 'created_at desc');
        $version_lastest = $version_lastest ? $version_lastest[0]['version'] : '0.0.0';
        $tmp_request = $this->RequestEntity->list(0, 0, ['id = '.$request_id], 0);
        foreach($tmp_request as $tmp_item) {
        }

        $status = false;

        $list   = new Listing($result, $total, $limit, $this->getColumns());
        return [
            'request_id' => $request_id,
            'list' => $list,
            'page' => $page,
            'start' => $start,
            'result' => $result,
            'status' => $status,
            'sort' => $sort,
            'user_id' => $this->user->get('id'),
            'url' => $this->router->url(),
            'link_update_relate_note' => $this->router->url('relate-note/update-alias'),
            'link_list' => $this->router->url('relate-notes/' . $request_id),
            'link_note' => $this->router->url('note/preview'),
            'link_list_relate_note' => $this->router->url('relate-notes/' . $request_id),
            'title_page_relate_note' => $title_page_relate_note,
            'token' => $this->token->value(),
        ];
    }

    public function javascript()
    {
        $filter = $this->filter()['form'];
        $urlVars = $this->request->get('urlVars');
        $request_id = (int) $urlVars['request_id'];

        return [
            'request_id' => $request_id,
            'link_list' => $this->router->url('relate-notes/' . $request_id),
            'link_form' => $this->router->url('relate-note/'. $request_id),
            'link_note' => $this->router->url('note'),
            'link_list_relate_note' => $this->router->url('relate-notes/' . $request_id),
            'token' => $this->token->value(),
        ];
    }

    public function getColumns()
    {
        return [
            'num' => '#',
            'title' => 'Title',
            'status' => 'Status',
            'created_at' => 'Created at',
            'col_last' => ' ',
        ];
    }

    protected $_filter;
    public function filter()
    {
        if( null === $this->_filter):
            $data = [
                'search' => $this->state('search', '', '', 'post', 'relate_note.search'),
                'limit' => $this->state('limit', 10, 'int', 'post', 'relate_note.limit'),
                'sort' => $this->state('sort', '', '', 'post', 'relate_note.sort')
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
