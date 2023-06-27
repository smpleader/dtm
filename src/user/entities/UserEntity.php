<?php
/**
 * SPT software - Entity
 * 
 * @project: https://github.com/smpleader/spt
 * @author: Pham Minh - smpleader
 * @description: Just a basic entity
 * 
 */

namespace DTM\user\entities;

use SPT\Storage\DB\Entity;

class UserEntity extends Entity
{
    protected $affix = 'User';
    protected $table = '#__users';
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
                'name' => [
                    'type' => 'varchar',
                    'limit' => 100,
                ],
                'username' => [
                    'type' => 'varchar',
                    'limit' => 100,
                ],
                'password' => [
                    // 'validate' => ['md5'],
                    'type' => 'varchar',
                    'limit' => 255,
                ],
                'email' => [
                    'type' => 'varchar',
                    'limit' => 255,
                ],
                'status' => [
                    'type' => 'tinyint',
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

    public function togglePublishment( $id, $action)
    {
        $item = $this->findByPK($id);
        $status = $action == 'active' ? 1 : 0;
        return $this->db->table( $this->table )->update([
            'status' => $status,
        ], ['id' => $id ]);
    }
    
    public function getGroups($user_id)
    {
        $list = $this->db->select( 'usermap.user_id, usergroup.name as group_name, usergroup.id as group_id' )
                        ->table( '#__user_usergroup_map as usermap' )
                        ->join( 'LEFT JOIN #__user_groups as usergroup ON usergroup.id = usermap.group_id ')
                        ->where(['usermap.user_id = ' .$user_id]);

        return $list->list(0, 0);
    }

}