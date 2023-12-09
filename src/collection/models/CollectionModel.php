<?php
namespace DTM\collection\models;

use SPT\Container\Client as Base;
use SPT\Traits\ErrorString;

class CollectionModel extends Base
{ 
    use ErrorString; 

    public function remove($id)
    {
        if (!$id)
        {
            return false;
        }

        $find = $this->CollectionEntity->findByPK($id);
        if (!$find)
        {
            $this->error = 'Invalid Filter';
            return false;
        }

        $try = $this->CollectionEntity->remove($id);
        if ($try)
        {
            // remove Shortcut
            if ($find['shortcut_id'])
            {
                $this->ShortcutModel->remove($find['shortcut_id']);
            }
        }

        return $try;
    }
    
    public function convertArray($data, $encode = true)
    {
        if ($encode)
        {
            if (is_array($data))
            {
                $data = implode('),(', $data);
                $data = $data ? '('. $data .')' : '';
            }
        }
        else
        {
            if(is_string($data))
            {
                $data = str_replace(['(', ')'], '', $data);
                $data = explode(',', $data);
            }
        }

        return $data;
    }

    public function createSlug($str, $delimiter = '-')
    {
        $slug = strtolower(trim(preg_replace('/[\s-]+/', $delimiter, preg_replace('/[^A-Za-z0-9-]+/', $delimiter, preg_replace('/[&]/', 'and', preg_replace('/[\']/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $str))))), $delimiter));
        return $slug;
    }

    public function add($data, $shortcut = true)
    {
        $data['tags'] = $data['tags'] ? $this->convertTag($data['tags']) : [];
        $data['tags'] = $data['tags'] ? $this->convertArray($data['tags']) : '';
        $data['filter_link'] = $this->createSlug($data['name']);
        $data['creator'] = $data['creator'] ? $this->convertArray($data['creator']) : '';
        $data['assignment'] = $data['assignment'] ? $this->convertArray($data['assignment']) : '';
        $filter = $this->CollectionEntity->bind($data);

        if (!$filter || !isset($filter['readyNew']) || !$filter['readyNew'])
        {
            $this->error = $this->CollectionEntity->getError();
            return false;
        }

        $newId =  $this->CollectionEntity->add($filter);

        if (!$newId)
        {
            $this->error = $this->CollectionEntity->getError();
            return false;
        }

        if($shortcut)
        {
            // create shortcut
            $this->updateShortcut($data, $newId);
        }

        return $newId;
    }

    public function update($data, $shortcut = true)
    {
        $data['tags'] = $data['tags'] ? $this->convertTag($data['tags']) : [];
        $data['tags'] = $data['tags'] ? $this->convertArray($data['tags']) : '';
        $data['filter_link'] = $this->createSlug($data['name']);
        $data['creator'] = $data['creator'] ? $this->convertArray($data['creator']) : '';
        $data['assignment'] = $data['assignment'] ? $this->convertArray($data['assignment']) : '';
        $filter = $this->CollectionEntity->bind($data);

        if (!$filter || !isset($filter['readyUpdate']) || !$filter['readyUpdate'])
        {
            $this->error = $this->CollectionEntity->getError();
            return false;
        }

        $try = $this->CollectionEntity->update($filter);
        if (!$try)
        {
            $this->error = $this->CollectionEntity->getError();
            return false;
        }

        if($shortcut)
        {
            $shortcut = $this->updateShortcut($data, $data['id']);
        }
        
        return $try;
    }

    public function getDetail($id)
    {
        if(!$id)
        {
            return [];
        }

        $data = $this->CollectionEntity->findByPK($id);
        if ($data)
        {
            $data['start_date'] = $data['start_date'] ? date('Y-m-d', strtotime($data['start_date'])) : '';
            $data['end_date'] = $data['end_date'] ? date('Y-m-d', strtotime($data['end_date'])) : '';
        }

        $data['tags'] = $data['tags'] ? $this->convertArray($data['tags'], false) : [];
        $data['creator'] = $data['creator'] ? $this->convertArray($data['creator'], false) : [];
        $data['assignment'] = $data['assignment'] ? $this->convertArray($data['assignment'], false) : [];

        if ($data['shortcut_id'])
        {
            $shortcut = $this->ShortcutModel->getDetail($data['shortcut_id']);
            $data['shortcut_name'] = $shortcut ? $shortcut['name'] : '';
            $data['shortcut_group'] = $shortcut ? $shortcut['group'] : '';
        }

        return $data;
    }

    public function checkFilterName($slug)
    {
        if (!$slug)
        {
            return false;
        }
        
        $slug = strtolower(urldecode($slug));
        $where = ['LOWER(filter_link) LIKE "'.$slug.'"'];
        $where[] = ['user_id' => $this->user->get('id')];
        $findOne = $this->CollectionEntity->findOne($where);
        
        if($findOne)
        {
            return $findOne;
        }

        return false;
    }

    public function updateShortcut($data, $filter_id)
    {
        if (!$data || !$filter_id)
        {
            return false;
        }

        $data['id'] = $filter_id;
        $shortcut = false;
        if ($filter_id)
        {
            $filter = $this->getDetail($filter_id);
            $shortcut = $filter['shortcut_id'] ? $this->ShortcutModel->getDetail($filter['shortcut_id']) : '';
        }

        if ($data['shortcut_name'] && $shortcut)
        {
            $try = $this->ShortcutModel->update([
                'name' => $data['shortcut_name'],
                'link' => $this->router->url('collection/'.$data['filter_link']),
                'group' => $data['shortcut_group'],
                'user_id' => $data['user_id'],
                'id' => $shortcut['id'],
                'modified_at' => date('Y-m-d H:i:s'),
                'modified_by' => $data['user_id'],
            ]);

            return $try;
        }
        elseif ($data['shortcut_name'])
        {
            $try = $this->ShortcutModel->add([
                'name' => $data['shortcut_name'],
                'link' => $this->router->url('collection/'.$data['filter_link']),
                'group' => $data['shortcut_group'],
                'user_id' => $data['user_id'],
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => $data['user_id'],
                'modified_at' => date('Y-m-d H:i:s'),
                'modified_by' => $data['user_id'],
            ]);

            if ($try)
            {
                $data = $this->CollectionEntity->bind($data);
                $data['shortcut_id'] = $try;
                $this->CollectionEntity->update($data);
            }

            return $try;
        }
        elseif($shortcut)
        {
            $try = $this->ShortcutModel->remove($shortcut['id']);
            if($try)
            {
                $data = $this->CollectionEntity->bind($data);
                $data['shortcut_id'] = 0;
                $this->CollectionEntity->update($data);
            }
            return $try;
        }

        return true;
    }

    public function getFilterWhere($filter)
    {
        if (!$filter || !$filter['id'])
        {
            return [];
        }
        $where = [];

        $tmp_tags = [];
        foreach($filter['tags'] as $tag)
        {
            $tmp_tags[] = 'tags LIKE "%('. $tag .')%"';
        }
        if ($tmp_tags)
        {
            $where[] = '('. implode(' OR ', $tmp_tags) .')';
        }

        $assignment_tmp = [];
        foreach($filter['assignment'] as $assignment)
        {
            $field = strpos($assignment, 'user') !== false ? 'assignee' : 'assign_user_group';
            $assignment = explode('-', $assignment);
            $id = end($assignment);
            $assignment_tmp[] = $field.' LIKE "%('. $id .')%"';
        }

        if ($assignment_tmp)
        {
            $where[] = '('. implode(' OR ', $assignment_tmp) .')';
        }

        $creator = [];
        foreach($filter['creator'] as $user)
        {
            $creator[] = 'created_by LIKE '. $user;
        }
        if ($creator)
        {
            $where[] = '('. implode(' OR ', $creator) .')';
        }

        if ($filter['start_date'])
        {
            $where[] = 'created_at >= "'. $filter['start_date'].'"';
        }

        if ($filter['end_date'])
        {
            $where[] = 'created_at <= "'. $filter['end_date'].'"';
        }

        return $where;
    }

    public function initCollection($user_id)
    {
        if (!$user_id)
        {
            return false;
        }

        // Create my note filter
        $try = $this->add([
            'user_id' => $user_id,
            'shortcut_id' => 0,
            'name' => 'My Notes',
            'select_object' => '',
            'start_date' => '',
            'end_date' => '',
            'tags' => [],
            'creator' => [$user_id],
            'assignment' => [],
            'shortcut_name' => 'My Notes',
            'shortcut_link' => '',
            'shortcut_group' => '',
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $user_id,
            'modified_at' => date('Y-m-d H:i:s'),
            'modified_by' => $user_id,
        ]);

        if (!$try)
        {
            return false;
        }

        $try = $this->add([
            'user_id' => $user_id,
            'shortcut_id' => 0,
            'name' => 'My Shares',
            'select_object' => '',
            'start_date' => '',
            'end_date' => '',
            'tags' => [],
            'creator' => [],
            'assignment' => ['user-'. $user_id],
            'shortcut_name' => 'My Shares',
            'shortcut_link' => '',
            'shortcut_group' => '',
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $user_id,
            'modified_at' => date('Y-m-d H:i:s'),
            'modified_by' => $user_id,
        ]);

        if (!$try)
        {
            return false;
        }

        return true;
    }

    public function convertTag($tags)
    {
        if(!$tags || !is_array($tags))
        {
            return '';
        }

        $list = [];
        foreach($tags as $item)
        {
            $tmp = $this->TagEntity->findByPK($item);
            if($tmp)
            {
                $list[] = $item;
            }
            else
            {
                $tag_tmp = explode(':', $item);
                if(count($tag_tmp) > 1 && $tag_tmp[1])
                {
                    $parent = $this->TagEntity->findOne(['name' => trim($tag_tmp[0])]);
                    if(!$parent)
                    {
                        $parent = $this->TagModel->add([
                            'name' => trim($tag_tmp[0]),
                            'description' => '',
                            'parent_id' => 0,
                        ]);
                    }
                    else
                    {
                        $parent = $parent['id'];
                    }

                    $child = $this->TagEntity->findOne(['name' => trim($tag_tmp[1])]);
                    if(!$child)
                    {
                        $child = $this->TagModel->add([
                            'name' => trim($tag_tmp[1]),
                            'description' => '',
                            'parent_id' => $parent,
                        ]);
                    }
                    else
                    {
                        $child = $child['id'];
                    }
                    
                    $list[] = $child;
                }
                elseif($tag_tmp[0])
                {
                    $try = $this->TagModel->add([
                        'name' => trim($tag_tmp[0]),
                        'description' => '',
                        'parent_id' => 0,
                    ]);
                    $list[] = $try;
                }
            }
        }

        return $list;
    }
}
