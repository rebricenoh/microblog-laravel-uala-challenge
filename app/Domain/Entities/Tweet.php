<?php

namespace App\Domain\Entities;

class Tweet
{
    private ?int $id = null;
    private int $userId;
    private string $content;
    private \DateTime $createdAt;

    public function __construct(int $userId, string $content)
    {
        $this->userId = $userId;
        $this->content = $content;
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
}
