<?php

namespace App\Domain\Entities;

class User
{
    private int $id;
    private string $name;
    private array $followers;
    private array $following;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->followers = [];
        $this->following = [];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getFollowers(): array
    {
        return $this->followers;
    }

    public function getFollowing(): array
    {
        return $this->following;
    }
}
