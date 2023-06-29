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

class AdminRequest extends ViewModel
{
    public static function register()
    {
        return [
            'layouts.backend.request.form',
            'layouts.backend.request.detail_request',
        ];
    }

    public function form()
    {
        $request = $this->container->get('request');
        $router = $this->container->get('router');
        $permission = $this->container->exists('PermissionModel') ? $this->container->get('PermissionModel') : null;

        $urlVars = $request->get('urlVars');
        
        $milestone_id = (int) $urlVars['milestone_id'];
        
        $form = new Form($this->getFormFields(), []);

        $allow_tag = $permission ? $permission->checkPermission(['tag_manager', 'tag_create']) : true;

        return [
            'form' => $form,
            'allow_tag' => $allow_tag ? 'true' : 'false',
            'url' => $router->url(),
            'link_user_search' => $router->url('request/find-user'),
            'link_list' => $router->url('requests/'. $milestone_id),
            'link_tag' => $router->url('tag/search'),
            'link_form' => $router->url('request/'. $milestone_id),
        ];
    }

    public function getFormFields()
    {
        $fields = [
            'id' => ['hidden'],
            'title' => [
                'text',
                'placeholder' => 'New Request',
                'showLabel' => false,
                'formClass' => 'form-control h-50-px fw-bold rounded-0 fs-3',
                'required' => 'required'
            ],
            'description' => ['textarea',
                'placeholder' => 'Enter description',
                'showLabel' => false,
                'formClass' => 'form-control rounded-0 border border-1 py-1 fs-4-5',
            ],
            'finished_at' => ['date',
                'placeholder' => 'Enter Finished At',
                'showLabel' => false,
                'formClass' => 'form-control rounded-0 border border-1 py-1 fs-4-5',
            ],
            'start_at' => ['date',
                'placeholder' => 'Enter Start At',
                'showLabel' => false,
                'formClass' => 'form-control rounded-0 border border-1 py-1 fs-4-5',
            ],
            'tags' => ['hidden',
            ],
            'assignment' => [
                'option',
                'type' => 'multiselect',
                'formClass' => 'form-select',
                'options' => [],
                'showLabel' => false,
                'placeholder' => 'Users',
                'formClass' => 'form-control',
            ],
            'token' => ['hidden',
                'default' => $this->container->get('token')->value(),
            ],
        ];

        return $fields;
    }

    public function detail_request()
    {
        $request = $this->container->get('request');
        $router = $this->container->get('router');
        $RequestEntity = $this->container->get('RequestEntity');
        $TagEntity = $this->container->get('TagEntity');
        $UserEntity = $this->container->get('UserEntity');
        $permission = $this->container->exists('PermissionModel') ? $this->container->get('PermissionModel') : null;
        $MilestoneEntity = $this->container->get('MilestoneEntity');

        $urlVars = $request->get('urlVars');
        $request_id = (int) $urlVars['request_id'];
        $request = $RequestEntity->findByPK($request_id);
        $milestone = $request ? $MilestoneEntity->findByPK($request['milestone_id']) : ['title' => '', 'id' => 0];
        
        if ($request)
        {
            $tags = $request['tags'] ? explode(',', $request['tags']) : [];
            $request['tags'] = [];
            foreach($tags as $tag)
            {
                $tmp = $TagEntity->findByPK($tag);
                if ($tmp)
                {
                    $request['tags'][] = $tmp;
                }
            }

            $assigns = $request['assignment'] ? json_decode($request['assignment']) : [];
            $selected_tmp = [];
            foreach($assigns as $assign)
            {
                $user_tmp = $UserEntity->findByPK($assign);
                if ($user_tmp)
                {
                    $selected_tmp[] = [
                        'id' => $assign,
                        'name' => $user_tmp['name'],
                    ];
                }
            }
            $request['assignment'] = $selected_tmp;
        }

        $allow_tag = $permission ? $permission->checkPermission(['tag_manager', 'tag_create']) : true;

        $title_page = '<a class="me-2" href="'.$router->url('notes').'">Notes</a> | <a class="ms-2" href="'. $router->url('requests/'. $milestone['id']).'" >'. $milestone['title'].'</a> >> Request: '. $request['title'].  '<a type="button" class="ms-3" id="edit-request"  data-bs-placement="top" data-bs-toggle="modal" data-bs-target="#formModalToggle" ><i class="fa-solid fa-pen-to-square"></i></a>';
        $note_types = [
            [
                'link' => $router->url('note/0?type=html'),
                'title' => 'Html',
            ],
            [
                'link' => $router->url('note/0?type=sheetjs'),
                'title' => 'Sheet',
            ],
            [
                'link' => $router->url('note/0?type=presenter'),
                'title' => 'Presenter',
            ],
        ];
        
        return [
            'request_id' => $request_id,
            'note_types' => $note_types,
            'allow_tag' => $allow_tag ? 'true' : 'false',
            'url' => $router->url(),
            'link_form_request' => $router->url('request/'. $milestone['id'] . '/' . $request['id']),
            'link_tag' => $router->url('tag/search'),
            'link_user_search' => $router->url('request/find-user'),
            'title_page' => $title_page,
            'request' => $request,
        ];
    }
}
