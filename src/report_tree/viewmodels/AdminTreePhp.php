<?php

/**
 * SPT software - ViewModel
 * 
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: A simple View Model
 * 
 */

namespace DTM\report_tree\viewmodels;

use SPT\Web\ViewModel;
use SPT\View\Gui\Form;

class AdminTreePhp extends ViewModel
{
    public static function register()
    {
        return [
            'layout'=>'backend.tree_php.form'
        ];
    }

    public function form()
    {
        $request = $this->container->get('request');
        $DiagramEntity = $this->container->get('DiagramEntity');
        $NoteEntity = $this->container->get('NoteEntity');
        $TreePhpModel = $this->container->get('TreePhpModel');
        $router = $this->container->get('router');

        $urlVars = $request->get('urlVars');
        $id = (int) $urlVars['id'];

        $data = $id ? $DiagramEntity->findByPK($id) : [];
        $ignore = [];
        $list_tree = [];
        if ($data)
        {
            $list_tree = $TreePhpModel->getTree($id);
            foreach($list_tree as $item)
            {
                $ignore[] = $item['note_id'];
            }
        }

        $form = new Form($this->getFormFields(), $data);
        return [
            'id' => $id,
            'form' => $form,
            'list_tree' => $list_tree,
            'data' => $data,
            'ignore' => $ignore,
            'title_page_edit' => $data && $data['title'] ? $data['title'] : 'New Diagrams',
            'url' => $router->url(),
            'link_note' => $router->url('note/'),
            'link_request' => $router->url('note/request'),
            'link_detail_request' => $router->url('detail-request'),
            'link_list' => $router->url('reports'),
            'link_form' => $router->url('tree-php'),
            'link_search' => $router->url('note/search'),
        ];
        
    }

    public function getFormFields()
    {
        $fields = [
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
            'notes' => [
                'hidden',
            ],
            'removes' => [
                'hidden',
            ],
            'structure' => [
                'hidden',
            ],
            'token' => ['hidden',
                'default' => $this->container->get('token')->value(),
            ],
        ];

        return $fields;
    }
}
