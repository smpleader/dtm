<?php

namespace DTM\user\libraries\providers;

use DTM\user\libraries\providers\ProviderBase;
use SPT\Storage\DB\Entity;

class UserProvider implements ProviderBase
{
    protected $entity;

    public function __construct(Entity $entity)
    {
        $this->entity = $entity;
    }

    /**
     * Retrieve a user by their unique identifier.
     *
     * @param  mixed  $id
     */
    public function retrieveById($id)
    {
        $user = $this->entity->findByPK($id);
        return $user;
    }

    /**
     * Retrieve a user by their unique identifier and "remember me" token.
     *
     * @param  mixed  $id
     * @param  string  $token
     */
    public function retrieveByToken($id, $token)
    {
        // todo
    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array  $credentials
     */
    public function retrieveByCredentials(array $credentials)
    {
        $username = $credentials['username'] ?? '';
        $password = $credentials['password'] ?? '';
        $user = $this->entity->findOne(['username' => $username, 'password' => md5($password)]);
        
        return $user;
    }
}
