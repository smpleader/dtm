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

use SPT\Web\ViewModel;
use SPT\View\Gui\Form;

class AdminNote extends ViewModel
{
    public static function register()
    {
        return [
            'layout' => [
                'backend.note.form',
                'backend.note.preview'
            ]
        ];
    }
    
    public function form()
    {
        $type = $this->request->get->get('type', 'html');
        $permission = $this->container->exists('PermissionModel') ? $this->PermissionModel : null;

        $id = 0;  
        
        $data = $this->NoteModel->getDetail($id);
        $data = $data ? $data : [];
        $allow_tag = $permission ? $permission->checkPermission(['tag_manager', 'tag_create']) : true;

        $allow_type = ['html', 'sheetjs', 'presenter'];
        $type = in_array($type, $allow_type) ? $type : 'html';

        $type = $data ? $data['type'] : $type;

        $form = new Form($this->getFormFields(), $data);
        return [
            'id' => $id,
            'form' => $form,
            'data' => $data,
            'type' => $type,
            'allow_tag' => $allow_tag ? 'true' : 'false',
            'data_version' => $data ? $data['versions'] : [],
            'attachments' => $data ? $data['attachments'] : [],
            'title_page_edit' => $data && $data['title'] ? $data['title'] : 'New Note',
            'url' => $this->router->url(),
            'link_list' => $this->router->url('notes'),
            'link_form' => $this->router->url('note'),
            'link_preview' => $id ? $this->router->url('note/preview/'. $id) : '',
            'link_form_attachment' => $this->router->url('attachment'),
            'link_form_download_attachment' => $this->router->url('download/attachment'),
            'link_tag' => $this->router->url('tag/search'),
        ];
        
    }

    public function preview()
    {
        $request = $this->request;
        $NoteEntity = $this->NoteEntity;
        $NoteHistoryEntity = $this->NoteHistoryEntity;
        $UserEntity = $this->UserEntity;
        $TagEntity = $this->TagEntity;
        $NoteModel = $this->NoteModel;
        $AttachmentEntity = $this->AttachmentEntity;
        $router = $this->router;

        $urlVars = $this->request->get('urlVars');
        $id = (int) $urlVars['id'];
        $version = $this->request->get->get('version', 0);

        $data = $this->NoteModel->getDetail($id);
        $data = $data ? $data : [];

        $form = new Form($this->getFormFields(), $data);
        $view_mode = $data ? 'true' : '';
        $title_page = $data['title'];
        $button_header = [
            [
                'link' => $this->router->url('notes'),
                'class' => 'btn btn-outline-secondary',
                'title' => 'Cancel',
            ],
            [
                'link' => $this->router->url('note/'. $id),
                'class' => 'btn ms-2 btn-outline-success',
                'title' => 'Edit',
            ],
        ];

        return [
            'id' => $id,
            'form' => $form,
            'data' => $data,
            'button_header' => $button_header,
            'view_mode' => $view_mode,
            'data_version' => $data ? $data['versions'] : [],
            'version' => $version,
            'attachments' => $data ? $data['attachments'] : [],
            'title_page' => $title_page,
            'url' => $this->router->url(),
            'link_list' => $this->router->url('notes'),
            'link_form' => $this->router->url('note'),
            'link_form_attachment' => $this->router->url('attachment'),
            'link_form_download_attachment' => $this->router->url('download/attachment'),
            'link_tag' => $this->router->url('tag/search'),
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
                'placeholder' => 'Notice',
                'formClass' => 'form-control',
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
