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

class AdminVersionLatest extends ViewModel
{
    public static function register()
    {
        return [
            'layout'=>'backend.version_latest.list',
        ];
    }

    public function list()
    {
        $version_latest = $this->VersionEntity->list(0, 1, [], 'created_at desc');
        $version_latest = $version_latest ? $version_latest[0] : [];
        // if(!$version_latest){
        //     $this->session->set('flashMsg', 'Not Found Version');
        // }
        $urlVars = $this->request->get('urlVars');
        $request_id = (int) $urlVars['request_id'];

        if (!$version_latest)
        {
            $version_latest['id'] = 0;
        }

        $tmp_request = $this->RequestEntity->findOne(['id' => $request_id]);

        $list = $this->VersionNoteEntity->list(0,0, ['version_id = '. $version_latest['id'], 'request_id = '. $request_id]);
        $list = $list ? $list : [];
        $request = $this->RequestEntity->findByPK($request_id);
        $milestone = $request ? $this->MilestoneEntity->findByPK($request['milestone_id']) : ['title' => '', 'id' => 0];
        
        if($version_latest) {
            $title_page = 'Version changelog : '. $version_latest['version'];
        } else {
            $title_page = 'Version changelog (Please create Version first)';
        }

        $version_lastest = $this->VersionEntity->list(0, 1, [], 'created_at desc');
        $version_lastest = $version_lastest ? $version_lastest[0]['version'] : '0.0.0';
        $tmp_request = $this->RequestEntity->list(0, 0, ['id = '.$request_id], 0);
        
        $status = false;

        return [
            'request_id' => $request_id,
            'list' => $list,
            'version_latest' => $version_latest,
            'status' => $status,
            'url' => $this->router->url(),
            'link_list' => $this->router->url('request-version/'. $request_id),
            'link_cancel' => $this->router->url('detail-request/'. $request_id),
            'title_page_version' => $title_page,
            'link_form' => $this->router->url('request-version/'. $request_id),
            'token' => $this->token->value(),
        ];
    }

}
