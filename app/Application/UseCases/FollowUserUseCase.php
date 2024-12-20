<?php

namespace App\Application\UseCases;

use App\Domain\Services\UserService;

class FollowUserUseCase
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function execute(int $followerId, int $followedId): bool
    {
        return $this->userService->followUser($followerId, $followedId);
    }
}
