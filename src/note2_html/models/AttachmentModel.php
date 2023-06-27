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

class AttachmentModel extends Base
{
    // Write your code here
    public function upload($file)
    {
        if($file && $file['name']) 
        {
            // get folder save attachment
            $path_attachment = $this->createFolderSave();

            $uploader = $this->file->setOptions([
                'overwrite' => true,
                'targetDir' => $path_attachment
            ]);
    
            // TODO: create dynamice fieldName for file
            $index = 0;
            $tmp_name = $file['name'];
            while(file_exists($path_attachment. '/' . $file['name']))
            {
                $file['name'] = $index. "_". $tmp_name;
                $index ++;
            }
            
            if( false === $uploader->upload($file) )
            {
                $this->session->set('flashMsg', 'Invalid attachment');
                return false;
            }
            
            return $file['name'];
        }

        return false;
    }

    public function add($files, $note_id)
    {
        if (!$note_id || !$files || !is_array($files))
        {
            return false;
        }

        if (is_array($files['name']) && $files['name'][0])
        {
            for ($i=0; $i < count($files['name']); $i++) 
            { 
                $file = [
                    'name' => $files['name'][$i],
                    'full_path' => $files['full_path'][$i],
                    'type' => $files['type'][$i],
                    'tmp_name' => $files['tmp_name'][$i],
                    'error' => $files['error'][$i],
                    'size' => $files['size'][$i],
                ];

                $try = $this->validate($file);
                if (!$try) return false;

                $file_name = $this->upload($file);
                if (!$file_name) return false;

                $try = $this->AttachmentEntity->add([
                    'note_id' => $note_id,
                    'name' => $file_name,
                    'path' => 'media/attachments/' . date('Y/m/d'). '/' . $file_name,
                    'uploaded_by' => $this->user->get('id'),
                    'uploaded_at' => date('Y-m-d H:i:s'),
                ]);

                if (!$try) return false;
            }
        }

        return false;
    }

    public function validate($file)
    {
        if (!$file || !$file['name'])
        {
            return false;
        }
        
        if ($this->config->extensionAllow &&  is_array($this->config->extensionAllow)) 
        {
            $extension = explode('.', $file['name']);
            $extension = end($extension);
            if (!in_array($extension, $this->config->extensionAllow)) 
            {
                $this->session->set('flashMsg', '.' . $extension . ' files are not allowed to upload');
                return false;
            }
        }

        return true;
    }

    public function remove($id)
    {
        $item = $this->AttachmentEntity->findByPK($id);
        if (!$item) {
            $this->session->set('flashMsg', 'Invalid attachment');
            return false;
        }

        if ($item['path'] && file_exists(PUBLIC_PATH . $item['path'])) {
            $try = unlink(PUBLIC_PATH . $item['path']);
            if (!$try) {
                $this->session->set('flashMsg', 'Remove attachment fail!');
                return false;
            }
        }

        $try = $this->AttachmentEntity->remove($id);

        return $try;
    }

    public function createFolderSave($dir = '')
    {
        if (!$dir) {
            $dir = MEDIA_PATH . 'attachments';
        }

        foreach ([date('Y'), date('m'), date('d')] as $item) 
        {
            $dir .= '/' . $item;

            if (!is_dir($dir) && !mkdir($dir)) 
            {
                return '';
            }
        }
        return $dir;
    }

    public function removeByNote($id)
    {
        if (!$id)
        {
            return false;
        }

        $list = $this->AttachmentEntity->list(0, 0, ['note_id' => $id]);
        foreach($list as $item)
        {
            $try = $this->remove($item['id']);
            if (!$try)
            {
                return false;
            }
        }

        return true;
    }

    public function getByNote($id)
    {
        if (!$id)
        {
            return false;
        }

        $list = $this->AttachmentEntity->list(0, 0, ['note_id' => $id]);
        return $list;
    }
}
