<?php

/**
 * SPT software - ViewModel
 * 
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: A simple View Model
 * 
 */

namespace DTM\note2\viewmodels;

use SPT\Web\ViewModel;
use SPT\View\Gui\Form;

class AdminNoteHistory extends ViewModel
{
    public static function register()
    {
        return [
            'layouts.backend.note_history.form',
        ];
    }

    public function form()
    {
        $urlVars = $this->request->get('urlVars');
        $id = (int) $urlVars['id'];

        $version = $id ? $this->NoteHistoryEntity->findByPK($id) : [];

        if ($version)
        {
            if ($version)
            {
                $user_tmp = $this->UserEntity->findByPK($version['created_by']);
                $version['created_by'] = $user_tmp ? $user_tmp['name'] : '';
                $data = json_decode($version['meta_data'], true);
                $data['id'] = $id;
                $data['title'] = $data['title'] . ' - '. $version['created_at']. ' - by '. $version['created_by'];
            }
        }

        if ($data)
        {
            $data['description'] = $this->NoteModel->replaceContent($data['description'], false);

            $data['description_sheetjs'] = base64_encode(strip_tags($data['description']));
            $data['description_presenter'] = $data['description'];
            $versions = $this->NoteHistoryEntity->list(0, 0, ['note_id' => $data['id']], 'id desc');
            $versions = $versions ? $versions : [];

            foreach($versions as &$item)
            {
                $user_tmp = $this->UserEntity->findByPK($item['created_by']);
                $item['created_by'] = $user_tmp ? $user_tmp['name'] : '';
            }

            $data['versions'] = $versions;
        }
        
        $data_tags = [];
        if (!empty($data['tags'])){
            $where[] = "(`id` IN (".$data['tags'].") )";
            $data_tags = $this->TagEntity->list(0, 1000, $where);
        }
        $attachments = $this->AttachmentEntity->list(0, 0, ['note_id = '. $id]);
        $form = new Form($this->getFormFields(), $data);
        $view_mode = true;

        return [
            'id' => $id,
            'form' => $form,
            'data' => $data,
            'view_mode' => $view_mode,
            'data_tags' => $data_tags,
            'version' => $version,
            'attachments' => $attachments,
            'title_page' => $data && $data['title'] ? $data['title'] : 'New Note',
            'url' => $this->router->url(),
            'link_list' => $this->router->url('note/'. $version['note_id']),
            'link_form' => $this->router->url('note/version'),
            'link_form_attachment' => $this->router->url('attachment'),
            'link_tag' => $this->router->url('tag'),
        ];
    }

    public function getFormFields()
    {
        $fields = [
            'description' => [
                'tinymce',
                'showLabel' => false,
                'formClass' => 'd-none',
            ],
            'description_sheetjs' => [
                'sheetjs',
                'showLabel' => false,
                'formClass' => 'field-sheetjs',
            ],
            'description_presenter' => [
                'presenter',
                'showLabel' => false,
                'formClass' => 'field-presenter',
            ],
            'note' => [
                'textarea',
                'showLabel' => false,
                'placeholder' => 'Note',
                'formClass' => 'form-control',
                ''
            ],
            'file' => [
                'file',
                'showLabel' => false,
                'formClass' => 'form-control',
            ],
            'title' => [
                'text',
                'showLabel' => false,
                'placeholder' => 'New Title',
                'formClass' => 'form-control border-0 border-bottom fs-2 py-0',
                'required' => 'required',
            ],
            'tags' => [
                'text',
                'showLabel' => false,
                'placeholder' => 'Tags',
                'formClass' => 'form-control',
            ],
            'token' => ['hidden',
                'default' => $this->token->value(),
            ],
        ];

        return $fields;
    }
}
