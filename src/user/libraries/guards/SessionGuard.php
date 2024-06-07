<?php

namespace DTM\user\libraries\guards;

use DTM\user\libraries\providers\ProviderBase;
use SPT\User\SPT\User as UserBase;
use SPT\Traits\ErrorString;
use SPT\Session\Instance as Session;

class SessionGuard implements Guard
{
    protected $user;
    protected $name;
    protected $provider;
    protected $session;

    public function __construct($name,
                                ProviderBase $provider,
                                Session $session)
    {
        $this->name = $name;
        $this->session = $session;
        $this->provider = $provider;
    }

    /**
     * Get a unique identifier for the auth session value.
     *
     * @return string
     */
    public function getName()
    {
        return 'login_'.$this->name;
    }

    /**
     * Determine if the current user is authenticated.
     *
     * @return bool
     */
    public function check()
    {
        return !(is_null($this->user()) || !$this->user()['id']);
    }

    /**
     * Determine if the current user is a guest.
     *
     * @return bool
     */
    public function guest()
    {
        return !$this->check();
    }

    /**
     * Get the currently authenticated user.
     */
    public function user()
    {
        if (! is_null($this->user)) {
            return $this->user;
        }

        $id = $this->session->get($this->getName());
        if (! is_null($id)) 
        {
            $this->user = $this->provider->retrieveById($id);
            unset($this->user['password']);
        }

        return $this->user;
    }


    public function login($username, $password)
    {
        $user = $this->provider->retrieveByCredentials(['username' => $username,'password' => $password]);
        if($user)
        {
            unset($user['password']);
            $this->user = $user;

            $this->session->set($this->getName(), $this->user['id']); 
            return $user;
        }

        return false;
    }

    /**
     * Get the ID for the currently authenticated user.
     *
     * @return int|string|null
     */
    public function id()
    {
        return $this->user() ? $this->user()->id : null;
    }

    /**
     * Validate a user's credentials.
     *
     * @param  array  $credentials
     * @return bool
     */
    public function validate(array $credentials = [])
    {
        // Todo
    }

    /**
     * Determine if the guard has a user instance.
     *
     * @return bool
     */
    public function hasUser()
    {
        // Todo
    }

    /**
     * Set the current user.
     *
     */
    public function setUser($user)
    {
        $this->user = $user;
        return;
    }
}
