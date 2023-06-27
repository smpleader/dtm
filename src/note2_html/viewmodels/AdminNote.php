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
            'layouts.backend.note.form',
            'layouts.backend.note.preview',
        ];
    }
    
    public function form()
    {
        $request = $this->request;
        $NoteEntity = $this->NoteEntity;
        $NoteHistoryEntity = $this->NoteHistoryEntity;
        $UserEntity = $this->UserEntity;
        $TagEntity = $this->TagEntity;
        $NoteModel = $this->NoteModel;
        $AttachmentEntity = $this->AttachmentEntity;
        $router = $this->router;
        $type = $this->request->get->get('type', 'html');
        $permission = $this->container->exists('PermissionModel') ? $this->PermissionModel : null;

        $id = 0;  
        
        $data = $NoteModel->getDetail($id);
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
            'url' => $router->url(),
            'link_list' => $router->url('notes'),
            'link_form' => $router->url('note'),
            'link_preview' => $id ? $router->url('note/preview/'. $id) : '',
            'link_form_attachment' => $router->url('attachment'),
            'link_form_download_attachment' => $router->url('download/attachment'),
            'link_tag' => $router->url('tag/search'),
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

        $urlVars = $request->get('urlVars');
        $id = (int) $urlVars['id'];
        $version = $request->get->get('version', 0);

        $data = $NoteModel->getDetail($id);
        $data = $data ? $data : [];

        $form = new Form($this->getFormFields(), $data);
        $view_mode = $data ? 'true' : '';
        $title_page = $data['title'];
        $button_header = [
            [
                'link' => $router->url('notes'),
                'class' => 'btn btn-outline-secondary',
                'title' => 'Cancel',
            ],
            [
                'link' => $router->url('note/'. $id),
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
            'url' => $router->url(),
            'link_list' => $router->url('notes'),
            'link_form' => $router->url('note'),
            'link_form_attachment' => $router->url('attachment'),
            'link_form_download_attachment' => $router->url('download/attachment'),
            'link_tag' => $router->url('tag/search'),
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
