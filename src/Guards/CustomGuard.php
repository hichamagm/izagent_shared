<?php

namespace Hichamagm\IzagentShared\Guards;

use Hichamagm\IzAgentShared\Auth\CustomUser;
use Hichamagm\IzAgentShared\Services\Auth\CustomEndpoints;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;

class CustomGuard implements Guard
{
    protected ?Authenticatable $user = null;
    protected CustomEndpoints $customAuthEndpoints;
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->customAuthEndpoints = new CustomEndpoints();
        $this->request = $request;
    }

    public function user(): ?Authenticatable
    {
        if ($this->user) {
            return $this->user;
        }

        $this->setUserFromToken();
        $this->setUserFromAppRequest();

        return $this->user;
    }

    protected function setUserFromToken(): void
    {
        $token = $this->request->bearerToken();

        if ($token) {
            $response = $this->customAuthEndpoints->userByToken($token);
            $data = $response->json();

            if ($response->successful() && isset($data['id'])) {
                $this->setUser(new CustomUser($data));
            }
        }
    }

    protected function setUserFromAppRequest(): void
    {
        $appToken = $this->request->header('X-App-Access-Token');
        $id = $this->request->header('X-User-Id');

        if ($appToken == env("APP_ACCESS_TOKEN") && $id) {
            $response = $this->customAuthEndpoints->user($id);
            $data = $response->json();

            if ($response->ok() && isset($data['id'])) {
                $this->setUser(new CustomUser($data));
            }
        }
    }

    public function id(): ?int
    {
        return $this->user()?->id;
    }

    public function check(): bool
    {
        return !is_null($this->id());
    }

    public function guest(): bool
    {
        return !$this->check();
    }

    public function hasUser()
    {
        return $this->check();
    }

    public function validate(array $credentials = []): bool
    {
        return false;
    }

    public function setUser(Authenticatable $user): void
    {
        $this->user = $user;
    }

}
