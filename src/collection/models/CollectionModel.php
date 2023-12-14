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
        $data['filters'] = $data['filters'] ? $this->convertTag($data['filters'], false) : [];
        $data['filters'] = $data['filters'] ? $this->convertArray($data['filters']) : '';
        $data['filter_link'] = $this->createSlug($data['name']);
        $data['creator'] = $data['creator'] ? $this->convertArray($data['creator']) : '';
        $data['assignment'] = $data['assignment'] ? $this->convertArray($data['assignment']) : '';
        $data['shares'] = $data['shares'] ? $this->convertArray($data['shares']) : '';
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
        $data['filters'] = $data['filters'] ? $this->convertTag($data['filters'], false) : [];
        $data['filters'] = $data['filters'] ? $this->convertArray($data['filters']) : '';
        $data['filter_link'] = $this->createSlug($data['name']);
        $data['creator'] = $data['creator'] ? $this->convertArray($data['creator']) : '';
        $data['assignment'] = $data['assignment'] ? $this->convertArray($data['assignment']) : '';
        $data['shares'] = $data['shares'] ? $this->convertArray($data['shares']) : '';
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

        $this->shareCollection($data['id']);

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
        $data['filters'] = $data['filters'] ? $this->convertArray($data['filters'], false) : [];
        $data['creator'] = $data['creator'] ? $this->convertArray($data['creator'], false) : [];
        $data['assignment'] = $data['assignment'] ? $this->convertArray($data['assignment'], false) : [];
        $data['shares'] = $data['shares'] ? $this->convertArray($data['shares'], false) : [];

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
        
        // where for shares collection
        $where_shares = [];
        $groups = $this->UserEntity->getGroups($this->user->get('id'));
        foreach($groups as $group)
        {
            $where_shares[] = 'shares LIKE "%(group-'. $group .')%"';
        }

        $where_shares[] = 'shares LIKE "%(user-'. $this->user->get('id') .')%"';
        $where_shares[] = 'user_id LIKE '. $this->user->get('id');

        $where[] = '('. implode(" OR ", $where_shares). ')';
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

    public function getFilterWhere($collection, $filter)
    {
        if (!$collection || !$collection['id'])
        {
            return [];
        }
        $where = [];

        $tmp_tags = [];
        foreach($collection['tags'] as $tag)
        {
            $tmp_tags[] = 'tags LIKE "%('. $tag .')%"';
        }
        if ($tmp_tags)
        {
            $where[] = '('. implode(' OR ', $tmp_tags) .')';
        }

        $filters = isset($collection['filters']) ? $collection['filters'] : [];
        foreach($filters as $item)
        {
            $child_tags = $this->TagEntity->list(0,0,['#__tags.parent_id LIKE '. $item]);
            $search = $filter->getField('parent_tag_'. $item)->value;

            if($search)
            {
                $tmp_tags = [];
                foreach($search as $tag)
                {
                    $tmp_tags[] = 'tags LIKE "%('. $tag .')%"';
                }
                if ($tmp_tags)
                {
                    $where[] = '('. implode(' OR ', $tmp_tags) .')';
                }
            }else{
                $tmp_tags = [];
                $tmp_tags[] = 'tags LIKE "%('. $item .')%"';
                if($child_tags)
                {
                    foreach($child_tags as $tag)
                    {
                        $tmp_tags[] = 'tags LIKE "%('. $tag['id'] .')%"';
                    }
                }

                if ($tmp_tags)
                {
                    $where[] = '('. implode(' OR ', $tmp_tags) .')';
                }
            }
        }

        $assignment_tmp = [];
        foreach($collection['assignment'] as $assignment)
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
        foreach($collection['creator'] as $user)
        {
            $creator[] = 'created_by LIKE '. $user;
        }
        if ($creator)
        {
            $where[] = '('. implode(' OR ', $creator) .')';
        }

        if ($collection['start_date'])
        {
            $where[] = 'created_at >= "'. $collection['start_date'].'"';
        }

        if ($collection['end_date'])
        {
            $where[] = 'created_at <= "'. $collection['end_date'].'"';
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

    public function convertTag($tags, $allow_parent = true)
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
                if(count($tag_tmp) > 1 && $tag_tmp[1] && $allow_parent)
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

    public function shareCollection($id)
    {
        if(!$id)
        {
            return false;
        }

        $collection = $this->getDetail($id);
        if (!$collection)
        {
            return false;
        }

        if ($collection['shares'] && $collection['shortcut_id'])
        {
            $shortcut = $this->ShortcutEntity->findByPK($collection['shortcut_id']);
            if(!$shortcut)
            {
                return false;
            }

            $users = [];
            $groups = [];
            foreach($collection['shares'] as $item)
            {
                if(strpos($item, 'user-') !== false)
                {
                    $users[] = str_replace('user-', '', $item);
                }

                if(strpos($item, 'group-') !== false)
                {
                    $groups[] = str_replace('group-', '', $item);
                }
            }

            foreach($groups as $group)
            {
                $list = $this->UserGroupEntity->list(0,0,['group_id' => $group]);
                foreach($list as $item)
                {
                    if (!in_array($item['user_id'], $users))
                    {
                        $users[] = $item['user_id'];
                    }
                }
            }

            foreach($users as $item)
            {
                $check = $this->ShortcutEntity->findOne(['user_id' => $item, 'link' => $shortcut['link']]);
                if (!$check)
                {
                    $try = $this->ShortcutEntity->add([
                        'name' => $shortcut['name'],
                        'link' => $shortcut['link'],
                        'group' => $shortcut['group'],
                        'user_id' => $item,
                        'created_at' => date('Y-m-d H:i:s'),
                        'created_by' => $this->user->get('id'),
                        'modified_at' => date('Y-m-d H:i:s'),
                        'modified_by' => $this->user->get('id'),
                    ]);
                }
            }
        }

        return true;
    }
}
