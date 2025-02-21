<?php

namespace Hichamagm\IzagentShared\Auth;

use Illuminate\Contracts\Auth\Authenticatable;

class CustomUser implements Authenticatable
{
    protected $attributes;

    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }

    public function __get($key)
    {
        return $this->attributes[$key] ?? null;
    }

    public function __set($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    public function toArray()
    {
        return $this->attributes;
    }

    public function getAuthIdentifier()
    {
        return $this->attributes['id'];
    }

    public function getAuthPassword()
    {
        return $this->attributes['password']; // Assuming password is returned by the auth service
    }

    public function getAuthIdentifierName()
    {
        return null;
    }

    public function getRememberToken()
    {
        return null;
    }

    public function setRememberToken($value)
    {
        return false;
    }

    public function getRememberTokenName()
    {
        return null;
    }
}
