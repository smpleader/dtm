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

class NoteHistoryModel extends Base
{ 
    public function add($data)
    {
        if (!$data || !isset($data['id']) || !$data['id'])
        {
            return false;
        }

        $history = [
            'title' => $data['title'],
            'tags' => $data['tags'],
            'note' => $data['note'],
            'type' => $data['type'],
            'description' => $data['description'],
            'modified_by' => $this->user->get('id'),
            'modified_at' => date('Y-m-d H:i:s'),
        ];

        $try_note = $this->NoteHistoryEntity->add([
            'note_id' => $data['id'],
            'meta_data' => json_encode($history),
            'created_by' => $this->user->get('id'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return $try_note;
    }

    public function removeByNote($id)
    {
        if (!$id)
        {
            return false;
        }

        $finds = $this->NoteHistoryEntity->list(0, 0, ['note_id' => $id]);
        foreach($finds as $item)
        {
            $try = $this->NoteHistoryEntity->remove($item['id']);
            if (!$try) return false;
        }

        return true;
    }

    public function rollback($id)
    {
        if (!$id)
        {
            return false;
        }

        $version = $this->NoteHistoryEntity->findByPK($id);
        if (!$version)
        {
            return false;
        }

        $data = json_decode($version['meta_data'], true);
        $data['id'] = $version['note_id'];
        $try = $this->NoteEntity->update($data);
        if (!$try) return false;

        $remove = $this->NoteHistoryEntity->list(0, 0, ['id > '.$id, 'note_id = '. $version['note_id']]);
        foreach($remove as $item)
        {
            $this->NoteHistoryEntity->remove($item['id']);
        }

        return $version['note_id'];
    }

    public function remove($id)
    {
        if (!$id) return false;
        
        return $this->NoteHistoryEntity->remove($id);
    }

    public function getVersions($id)
    {
        if (!$id)
        {
            return false;
        }

        $versions = $this->NoteHistoryEntity->list(0, 0, ['note_id' => $id], 'id desc');
        $versions = $versions ? $versions : [];

        foreach($versions as &$item)
        {
            $user_tmp = $this->UserEntity->findByPK($item['created_by']);
            $item['created_by'] = $user_tmp ? $user_tmp['name'] : '';
        }

        return $versions;
    }
}
