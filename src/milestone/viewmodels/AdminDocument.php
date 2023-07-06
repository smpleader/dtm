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

class AdminDocument extends ViewModel
{
    public static function register()
    {
        return ['layout'=>'backend.document.form'];
    }
    public function form()
    {
        $urlVars = $this->request->get('urlVars');
        $request_id = (int) $urlVars['request_id'];

        $editor = $this->request->get->get('editor', '');
        $data = $request_id ? $this->DocumentEntity->findOne(['request_id = '. $request_id ]) : [];
        $data = $data ? $data : ['id' => 0];
        $editor = 1;
        $form = new Form($this->getFormFields(), $data ? $data : []);
        $request = $this->RequestEntity->findByPK($request_id);
        $milestone = $request ? $this->MilestoneEntity->findByPK($request['milestone_id']) : ['title' => '', 'id' => 0];
        $title_page = 'Document';

        $history = $this->DocumentHistoryEntity->list(0,0,['document_id = '.$data['id']], 'id DESC');
        if($history)
        {
            foreach($history as &$item)
            {
                $user_tmp = $this->UserEntity->findByPK($item['modified_by']);
                if ($user_tmp)
                {
                    $item['modified_by'] = $user_tmp['name'];
                }
            }
        }
        $discussion = $this->DiscussionEntity->list(0, 0, ['document_id = '. $data['id']], 'sent_at asc');
        $discussion = $discussion ? $discussion : [];
        foreach ($discussion as &$item)
        {
            $user_tmp = $this->UserEntity->findByPK($item['user_id']);
            $item['user'] = $user_tmp ? $user_tmp['name'] : '';
            $item['message'] = str_replace("\n", '<br>', $item['message']);
        }

        $version_lastest = $this->VersionEntity->list(0, 1, [], 'created_at desc');
        $version_lastest = $version_lastest ? $version_lastest[0]['version'] : '0.0.0';
        $tmp_request = $this->RequestEntity->list(0, 0, ['id = '.$request_id], 0);
        foreach($tmp_request as $tmp_item) {
        }
        $status = false;

        return [
            'form' => $form,
            'data' => $data,
            'history' => $history ? $history : [],
            'discussion' => $discussion ? $discussion : [],
            'status' => $status,
            'editor' => $editor,
            'user_id' => $this->user->get('id'),
            'title_page_document' => $title_page,
            'url' => $this->router->url(),
            'link_list' => $this->router->url('detail-request/'. $request_id),
            'link_form' => $this->router->url('document/'. $request_id),
            'link_form_comment' => $this->router->url('discussion/'. $request_id),
        ];
        
    }

    public function getFormFields()
    {
        $fields = [
            'id' => ['hidden'],
            'description' => ['tinymce',
                'showLabel' => false,
                'formClass' => 'form-control',
                'rows' => 25,
            ],
            
            'token' => ['hidden',
                'default' => $this->token->value(),
            ],
        ];

        return $fields;
    }
}


