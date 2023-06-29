<?php
/**
 * SPT software - Model
 * 
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: Just a basic model
 * 
 */

namespace DTM\milestone\models;

use SPT\Container\Client as Base;

class RequestModel extends Base 
{ 
    // Write your code here
    public function remove($id)
    {
        $tasks = $this->TaskEntity->list(0, 0, ['request_id = '. $id]);
        $relate_notes = $this->RelateNoteEntity->list(0, 0, ['request_id = '. $id]);
        $document = $this->DocumentEntity->list(0, 0, ['request_id = '. $id]);
        $version_notes = [];
        if (!$this->container->exists('VersionEntity'))
        {
            $version_notes = $this->VersionNoteEntity->list(0, 0, ['request_id = '. $id]);
        }
        $try = $this->RequestEntity->remove($id);
        if ($try)
        {
            foreach ($tasks as $task)
            {
                $this->TaskEntity->remove($task['id']);
            }

            foreach ($relate_notes as $note)
            {
                $this->RelateNoteEntity->remove($note['id']);
            }

            foreach ($version_notes as $note)
            {
                $this->VersionNoteEntity->remove($note['id']);
            }

            foreach ($document as $item)
            {
                $this->DocumentModel->remove($item['id']);
            }
        }

        return $try;
    }   

    public function excerpt($content, $limit = 10)
    {
        $content = explode(' ', $content);
        $ex = count($content) > $limit ? ' ...' : '';
        $content = array_splice($content, 0, 10);
        $string = implode(' ', $content);
        
        return $string . $ex;
    }   

    public function validate($data)
    {
        if (!$data || !is_array($data))
        {
            return false;
        }

        $exist = $this->MilestoneEntity->findByPK($data['milestone_id']);
        if (!$exist)
        {
            $this->session->set('flashMsg', 'Invalid Milestone');
            return false;
        }

        if (!$data['title'])
        {
            $this->session->set('flashMsg', 'Error: Title can\'t empty! ');
            return false;
        }

        $data['tags'] = $data['tags'] ? $this->getTag($data['tags']) : '';
        
        if(!$exist) {
            return $this->app->redirect(
                $this->router->url('milestones')
            );
        }

        $data['assignment'] = $data['assignment'] ? json_encode($data['assignment']) : '';
        $data['start_at'] = $data['start_at'] ? $data['start_at'] : null;
        $data['finished_at'] = $data['finished_at'] ? $data['finished_at'] : null;

        return $data;
    }

    public function getTag($tags)
    {
        $listTag = explode(',', $tags);
        $tags_tmp = [];
        foreach($listTag as $tag)
        {
            if (!$tag) continue;
            $find = $this->TagEntity->findOne(['id', $tag]);
            if ($find)
            {
                $tags_tmp[] = $tag;
            }
            else
            {
                $find_tmp = $this->TagEntity->findOne(['name' => $tag]);
                if ($find_tmp)
                {
                    $tags_tmp[] = $find_tmp['id'];
                }
                else
                {
                    $new_tag = $this->TagEntity->add(['name' => $tag]);
                    if ($new_tag)
                    {
                        $tags_tmp[] = $new_tag;
                    }
                }
            }
        }

        return implode(',', $tags_tmp);
    }

    public function add($data)
    {
        if (!$data || !is_array($data))
        {
            return false;
        }

        $newId =  $this->RequestEntity->add([
            'milestone_id' => $data['milestone_id'],
            'title' => $data['title'],
            'tags' => $data['tags'],
            'assignment' => $data['assignment'],
            'description' => $data['description'],
            'start_at' => $data['start_at'],
            'finished_at' => $data['finished_at'],
            'created_by' => $this->user->get('id'),
            'created_at' => date('Y-m-d H:i:s'),
            'modified_by' => $this->user->get('id'),
            'modified_at' => date('Y-m-d H:i:s')
        ]);

        return $newId;
    }

    public function update($data)
    {
        if (!$data || !is_array($data) || !$data['id'])
        {
            return false;
        }

        $try = $this->RequestEntity->update([
            'milestone_id' => $data['milestone_id'],
            'title' => $data['title'],
            'tags' => $data['tags'],
            'assignment' => $data['assignment'],
            'description' => $data['description'],
            'start_at' => $data['start_at'],
            'finished_at' => $data['finished_at'],
            'modified_by' => $this->user->get('id'),
            'modified_at' => date('Y-m-d H:i:s'),
            'id' => $data['id'],
        ]);

        return $try;
    }

    public function getVersionNote($id)
    {
        if (!$id)
        {
            return false;
        }
        $list = $this->VersionNoteEntity->list(0,0, ['request_id = '. $id]);
        $list = $list ? $list : [];

        return $list;
    }

    public function addVersion($data)
    {
        if (!$data || !is_array($data) || !($data['request_id']) || !$data['log'])
        {
            return false;
        }
        $version_latest = $this->VersionEntity->list(0, 1, [], 'created_at desc');
        $version_latest = $version_latest ? $version_latest[0] : [];
        if( !$version_latest )
        {
            return false;
        }

        // TODO: validate new add
        $newId =  $this->VersionNoteEntity->add([
            'version_id' => $version_latest['id'],
            'log' => $data['log'],
            'request_id' => $data['request_id'],
            'created_by' => $this->user->get('id'),
            'created_at' => date('Y-m-d H:i:s'),
            'modified_by' => $this->user->get('id'),
            'modified_at' => date('Y-m-d H:i:s')
        ]);

        return $newId;
    }

    public function updateVersion($data)
    {
        if (!$data || !is_array($data) || !($data['id']) || !$data['log'])
        {
            return false;
        }

        $try = $this->VersionNoteEntity->update([
            'log' => $data['log'],
            'modified_by' => $this->user->get('id'),
            'modified_at' => date('Y-m-d H:i:s'),
            'id' => $data['id'],
        ]);

        return $try;
    }

    public function removeVersion($id)
    {
        if (!$id) return false;
        $try = $this->VersionNoteEntity->remove($id);
        
        return $try;
    }
}
