<?php
/**
 * SPT software - Entity
 * 
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: Just a basic entity
 * 
 */

namespace DTM\report_tree\entities;

use SPT\Storage\DB\Entity;

class TreeStructureEntity extends Entity
{
    protected $table = '#__tree_structure';
    protected $pk = 'id';

    public function getFields()
    {
        return [
                'id' => [
                    'type' => 'int',
                    'pk' => 1,
                    'option' => 'unsigned',
                    'extra' => 'auto_increment',
                ],
                'diagram_id' => [
                    'type' => 'int',
                ],
                'note_id' => [
                    'type' => 'int',
                ],
                'parent_id' => [
                    'type' => 'int',
                ],
                'tree_position' => [
                    'type' => 'int',
                ],
                'tree_level' => [
                    'type' => 'int',
                ],
                'tree_left' => [
                    'type' => 'int',
                ],
                'tree_right' => [
                    'type' => 'int',
                ],
        ];
    }

    public function rebuild($diagram_id)
    {
        $data = $this->getTreeWithChildren($diagram_id);
        $n = 0;
        $level = 0;
        
        $this->rebuildGenerateTreeData($data, 0, 0, $n);
        foreach ($data as $id => $row) 
        {
            if ($id == '0') 
            {
                continue;
            }
            $try = $this->update([
                'tree_level' => $row['tree_level'],
                'tree_right' => $row['tree_right'],
                'tree_left' => $row['tree_left'],
                'id' => $row['id'],
            ]);
            if (!$try)
            {
                return false;
            }
        }
        return true;
    }

    public function getTreeWithChildren($diagram_id)
    {
        $list = $this->list(0, 0, ['diagram_id ='. $diagram_id], 'tree_position ASC');
        $array = [];
        foreach ($list as $item) {
            $array[$item['note_id']] = $item;
            $array[$item['note_id']]['children'] = [];
        }

        $array = $this->getTreeRebuildChildren($array);
        return $array;
    }

    public function getTreeRebuildChildren($array)
    {
        foreach ($array as $id => $row) {
            if (isset($row['parent_id']) && ($row['note_id'] != $row['parent_id'])) {
                $array[$row['parent_id']]['children'][$id] = $id;
            }
        }

        return $array;
    }

    protected function rebuildGenerateTreeData(array &$array, int $id, int $level, int &$n)
    {
        $array[$id]['tree_level'] = $level;
        $array[$id]['tree_left'] = $n++;
        // loop over the node's children and process their data
        // before assigning the right value
        if (isset($array[$id]['children']))
        {
            foreach ($array[$id]['children'] as $child_id) {
                $this->rebuildGenerateTreeData($array, $child_id, $level + 1, $n);
            }
        }

        $array[$id]['tree_right'] = $n++;
    }
}