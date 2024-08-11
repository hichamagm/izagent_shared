<?php

namespace Hichamagm\IzagentShared\Models;

class Domain
{
    public $id;
    public $name;
    public $status;
    public $service;
    public $type;
    public $nextFetch;
    public $errorMsg;
    public $sslEnabled;
    public $createdAt;
    public $updatedAt;

    public function __construct(array $attributes)
    {
        $this->id = $attributes['id'] ?? null;
        $this->name = $attributes['name'] ?? null;
        $this->status = $attributes['status'] ?? null;
        $this->service = $attributes['service'] ?? null;
        $this->type = $attributes['type'] ?? null;
        $this->errorMsg = $attributes['errorMsg'] ?? null;
        $this->sslEnabled = $attributes['ssl_enabled'] ?? null;
        $this->nextFetch = $attributes['next_fetch'] ?? null;
        $this->createdAt = $attributes['created_at'] ?? null;
        $this->updatedAt = $attributes['updated_at'] ?? null;
    }

    public static function fromArray(array $attributes)
    {
        return new self($attributes);
    }

    public static function fromCollection(array $items)
    {
        return array_map(function ($item) {
            return new self($item);
        }, $items);
    }
}