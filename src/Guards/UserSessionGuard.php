<?php

namespace Hichamagm\IzagentShared\Guards;

use Illuminate\Contracts\Auth\Guard;

class UserSessionGuard implements Guard
{
    protected $session;
    protected $user;

    public function __construct($session)
    {
        $this->session = $session;
    }

    public function user()
    {
        if ($this->user) {
            return $this->user;
        }

        $id = $this->session->get('user_id');

        if ($id) {
            $this->user = (object) ['id' => $id]; // Creating a simple user object
        }

        return $this->user;
    }

    public function id()
    {
        return $this->session->get('user_id');
    }

    public function check()
    {
        return !is_null($this->id());
    }

    public function guest()
    {
        return !$this->check();
    }

    public function setUser($user)
    {
        $this->user = $user;
        $this->session->put('user_id', $user->id);
    }

    public function validate(array $credentials = [])
    {
        return false;
    }

    public function hasUser()
    {
        return !is_null($this->id());
    }
}