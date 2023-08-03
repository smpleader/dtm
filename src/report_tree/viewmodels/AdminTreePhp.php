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
use SPT\Web\Gui\Form;

class AdminTreePhp extends ViewModel
{
    public static function register()
    {
        return [
            'layout'=>'backend.report.form'
        ];
    }

    private function getItem()
    {
        $urlVars = $this->request->get('urlVars');
        $id = $urlVars && isset($urlVars['id']) ? (int) $urlVars['id'] : 0;

        $data = $this->TreePhpModel->getDetail($id);

        return $data;
    }

    public function form()
    {
        $data = $this->getItem();
        $data = $data ? $data : [];
        $id  = $data ? $data['id'] : '';

        $form = new Form($this->getFormFields(), $data);

        return [
            'id' => $id,
            'form' => $form,
            'data' => $data,
            'title_page_edit' => $data && $data['title'] ? $data['title'] : 'New Diagrams',
            'url' => $this->router->url(),
            'link_request' => $this->router->url('report/find-request'),
            'link_note' => $this->router->url('note2-detail'),
            'link_list' => $this->router->url('reports'),
            'link_form' => $id ? $this->router->url('report/detail') : $this->router->url('new-report/tree'),
            'link_search' => $this->router->url('note2/search'),
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
