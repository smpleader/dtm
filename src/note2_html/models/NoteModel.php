<?php
/**
 * SPT software - Model
 * 
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: Just a basic model
 * 
 */

namespace DTM\note2_html\models;

use SPT\Container\Client as Base;

class NoteModel extends Base
{ 
    // Write your code here
    public function replaceContent($content, $encode = true)
    {
        $replace = $encode ? '_sdm_app_domain_' : $this->router->url();
        $search = $encode ? $this->router->url() : '_sdm_app_domain_';
        
        $content = str_replace($search, $replace, $content);

        return $content;
    }

    public function validate($data)
    {
        if (!is_array($data))
        {
            return false;
        }

        if (!isset($data['title']) || !$data['title'] || !$data)
        {
            $this->session->set('flashMsg', 'Error: Title is required! ');
            return false;
        }

        $where = ['title = "'. $data['title']. '"'];
        if (isset($data['id']) && $data['id'])
        {
            $where[] = 'id <> '. $data['id'];
        }

        $find = $this->NoteEntity->findOne($where);
        if ($find)
        {
            $this->session->set('flashMsg', 'Error: Title already used! ');
            return false;
        }

        return true;
    }

    public function add($data)
    {
        if (!$data)
        {
            return false;
        }

        $data['tags'] = $data['tags'] ? $this->getTag($data['tags']) : '';
        $data['type'] = !$data['type'] ? 'html' : $data['type'];

        $description = $data['description'];
        if ($data['type'] == 'sheetjs')
        {
            $description = isset($data['description_sheetjs']) ? base64_decode($data['description_sheetjs']) : '';
        }
        if ($data['type'] == 'presenter')
        {
            $description = isset($data['description_presenter']) ? $data['description_presenter'] : '';
        }

        $data['description'] = $this->replaceContent($description);

        $newId =  $this->NoteEntity->add([
            'title' => $data['title'],
            'tags' => $data['tags'],
            'note' => $data['note'],
            'type' => $data['type'],
            'description' => $data['description'],
            'created_by' => $this->user->get('id'),
            'created_at' => date('Y-m-d H:i:s'),
            'modified_by' => $this->user->get('id'),
            'modified_at' => date('Y-m-d H:i:s')
        ]);

        if ($newId)
        {
            if (isset($data['files']) && $data['files'])
            {
                $this->AttachmentModel->add($data['files'], $newId);
            }
        }

        return $newId;
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

    public function update($data)
    {
        if (!$data || !isset($data['id']) || !$data['id'])
        {
            return false;
        }

        $data['tags'] = $data['tags'] ? $this->getTag($data['tags']) : '';
        $data['type'] = !$data['type'] ? 'html' : $data['type'];

        $description = $data['description'];
        if ($data['type'] == 'sheetjs')
        {
            $description = isset($data['description_sheetjs']) ? base64_decode($data['description_sheetjs']) : '';
        }
        if ($data['type'] == 'presenter')
        {
            $description = isset($data['description_presenter']) ? $data['description_presenter'] : '';
        }

        $data['description'] = $this->replaceContent($description);

        $try =  $this->NoteEntity->update([
            'title' => $data['title'],
            'tags' => $data['tags'],
            'note' => $data['note'],
            'type' => $data['type'],
            'description' => $data['description'],
            'modified_by' => $this->user->get('id'),
            'modified_at' => date('Y-m-d H:i:s'),
            'id' => $data['id'],
        ]);

        if ($try)
        {
            if (isset($data['files']) && $data['files'])
            {
                $this->AttachmentModel->add($data['files'], $data['id']);
            }

            // Save History Note
            $this->NoteHistoryModel->add($data);
        }

        return $try;
    }

    public function remove($id)
    {
        if (!$id)
        {
            return false;
        }

        // remove attachment
        $this->AttachmentModel->removeByNote($id);
        // remove Relate Note
        $this->RelateNoteModel->removeByNote($id);
        // remove history
        $this->NoteHistoryModel->removeByNote($id);

        $try = $this->NoteEntity->remove($id);
        return $try;
    }

    public function searchAjax($search, $ignore)
    {
        $where = [];
        if ($search)
        {
            $tags = $this->TagEntity->list(0, 0, ["`name` LIKE '%" . $search . "%' "]);
            $where[] = "(`note` LIKE '%" . $search . "%')";
            $where[] = "(`title` LIKE '%" . $search . "%')";
            if ($tags) {
                foreach ($tags as $tag) {
                    $where[] = "(`tags` = '" . $tag['id'] . "'" .
                        " OR `tags` LIKE '%" . ',' . $tag['id'] . "'" .
                        " OR `tags` LIKE '" . $tag['id'] . ',' . "%'" .
                        " OR `tags` LIKE '%" . ',' . $tag['id'] . ',' . "%' )";
                }
            }
            $where = ['('. implode(" OR ", $where). ')'];
        }

        if ($ignore)
        {
            $where[] = 'id NOT IN('.$ignore.')';
        }

        $result = $this->NoteEntity->list(0, 0, $where, '`title` asc');
        $result = $result ? $result : [];
        return $result;
    }

    public function getRequest($id)
    {
        if (!$id)
        {
            return false;
        }
        
        $list = $this->RelateNoteEntity->list(0, 0, ['note_id = '. $id]);
        $result = [];
        foreach($list as &$item)
        {
            $request = $this->RequestEntity->findByPK($item['request_id']);
            if ($request)
            {
                $request['start_at'] = $request['start_at'] && $request['start_at'] != '0000-00-00 00:00:00' ? date('m-d-Y', strtotime($request['start_at'])) : '';
                $request['finished_at'] = $request['finished_at'] && $request['finished_at'] != '0000-00-00 00:00:00' ? date('m-d-Y', strtotime($request['finished_at'])) : '';
                $result[] = $request;
            }
        }

        return $result;
    }

    public function getDetail($id)
    {
        if (!$id)
        {
            return false;
        }

        $data = $this->NoteEntity->findByPK($id);
        if (!$data)
        {
            return false;
        }

        $data['description'] = $this->replaceContent($data['description'], false);
        $data['description_sheetjs'] = base64_encode(strip_tags($data['description']));
        $data['description_presenter'] = $data['description'];

        $data['versions'] = $this->NoteHistoryModel->getVersions($id);
        $data['type'] = !$data['type'] ? 'html' : $data['type'];

        $tag_tmp = $data['tags'] ? explode(',', $data['tags']) : [];
        
        $data['data_tags'] = [];
        foreach($tag_tmp as $tag)
        {
            $tmp = $this->TagEntity->findByPK($tag);
            if ($tmp)
            {
                $data['data_tags'][$tmp['id']] = $tmp['name'];
            }
        }

        $data['tags'] = implode(', ', $data['data_tags']);
        
        $data['attachments'] = $this->AttachmentModel->getByNote($id);
        return $data;
    }
}
