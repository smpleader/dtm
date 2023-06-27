<?php
/**
 * SPT software - Entity
 * 
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: Just a basic entity
 * 
 */

namespace DTM\milestone\entities;

use SPT\Storage\DB\Entity;

class RequestEntity extends Entity
{
    protected $table = '#__requests';
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
                'milestone_id' => [
                    'type' => 'int',
                    'option' => 'unsigned',
                ],
                'title' => [
                    'type' => 'varchar',
                    'limit' => 255,
                ],
                'start_at' => [
                    'type' => 'datetime',
                    'null' => 'YES',
                ],
                'tags' => [
                    'type' => 'text',
                    'null' => 'YES',
                ],
                'assignment' => [
                    'type' => 'text',
                    'null' => 'YES',
                ],
                'description' => [
                    'type' => 'text',
                    'null' => 'YES',
                ],
                'deadline_at' => [
                    'type' => 'datetime',
                    'null' => 'YES',
                ],
                'finished_at' => [
                    'type' => 'datetime',
                    'null' => 'YES',
                ],
                'created_at' => [
                    'type' => 'datetime',
                    'null' => 'YES',
                ],
                'created_by' => [
                    'type' => 'int',
                    'option' => 'unsigned',
                ],
                'modified_at' => [
                    'type' => 'datetime',
                    'null' => 'YES',
                ],
                'modified_by' => [
                    'type' => 'int',
                    'option' => 'unsigned',
                ],
        ];
    }

    public function toggleStatus( $id, $action)
    {
        $item = $this->findByPK($id);
        return $this->db->table( $this->table )->update([
            'status' => $status,
        ], ['id' => $id ]);
    }
}