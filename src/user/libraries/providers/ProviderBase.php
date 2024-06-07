<?php

namespace DTM\user\libraries\providers;

interface ProviderBase
{
    /**
     * Retrieve a user by their unique identifier.
     *
     * @param  mixed  $id
     */
    public function retrieveById($id);

    /**
     * Retrieve a user by their unique identifier and "remember me" token.
     *
     * @param  mixed  $id
     * @param  string  $token
     */
    public function retrieveByToken($id, $token);

    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array  $credentials
     */
    public function retrieveByCredentials(array $credentials);
}
