<?php
/**
 * SPT software - Model
 * 
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: Just a basic model
 * 
 */

namespace DTM\media\models;

use SPT\Container\Client as Base;

class MediaModel extends Base
{ 
    // Write your code here
    use \SPT\Traits\ErrorString;

    public function add($data)
    {
        $files = [];
        if (!$data || !$data['file'] || !$data['file']['name'])
        {
            $this->error = 'Invalid file';
            return false;
        }

        for ($i=0; $i < count($data['file']['name']); $i++) 
        { 
            $file = [
                'name' => $data['file']['name'][$i],
                'full_path' => $data['file']['full_path'][$i],
                'type' => $data['file']['type'][$i],
                'tmp_name' => $data['file']['tmp_name'][$i],
                'error' => $data['file']['error'][$i],
                'size' => $data['file']['size'][$i],
            ];

            $files[] = $file;
        }

        foreach($files as $file)
        {
            $path = $this->upload($file);
            if (!$path)
            {
                return false;
            }

            $data = [
                'name' => basename($path),
                'path' => $path,
                'note' => '',
                'type' => $file['type'],
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => $this->user->get('id'),
                'modified_at' => date('Y-m-d H:i:s'),
                'modified_by' => $this->user->get('id'),
            ];

            $data = $this->MediaEntity->bind($data);
            $try = $this->MediaEntity->add($data);

            if (!$try)
            {
                $this->error = $this->MediaEntity->getError();
                return false;
            }
        }

        return true;
    }

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
    
            if (!$path_attachment)
            {
                $this->error = "Can't create folder media";
                return false;
            }
            // TODO: create dynamice fieldName for file
            $index = 0;
            $tmp_name = $file['name'];
            while(file_exists($path_attachment. '/' . $file['name']))
            {
                $file['name'] = 'media/attachments/' . date('Y/m/d'). '/'. $index. "_". $tmp_name;
                $index ++;
            }
            if( false === $uploader->upload($file) )
            {
                $this->error = 'Invalid attachment';
                return false;
            }

            return $file['name'];
        }

        return false;
    }

    public function createFolderSave($dir = '')
    {
        if (!$dir) {
            $dir = MEDIA_PATH ;
        }

        foreach (['attachments', date('Y'), date('m'), date('d')] as $item) 
        {
            $dir .= '/' . $item;

            if (!is_dir($dir) && !mkdir($dir)) 
            {
                return '';
            }
        }
        return $dir;
    }

    public function remove($id)
    {
        if (!$id)
        {
            $this->error = 'Invalid id';
            return false;
        } 

        $find = $this->MediaEntity->findByPK($id);
        if (!$find)
        {
            $this->error = 'Invalid media';
            return false;
        }
        
        if(file_exists(PUBLIC_PATH. $find['path']))
        {
            $try = unlink(PUBLIC_PATH. $find['path']);
        }

        $try = $this->MediaEntity->remove($id);
        return $try;
    }

    public function search($search, $page, $limit)
    {
        $where = [];
        if ($search)
        {
            $where[] = 'name LIKE "%'. $search .'%"';
        }

        $start  = ($page - 1) * $limit;
        $result = $this->MediaEntity->list($start, $limit, $where, 'created_at desc');
        $total = $this->MediaEntity->getListTotal();
        
        return [
            'list' => $result,
            'total' => $total,
        ];
    }
}
