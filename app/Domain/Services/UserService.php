<?php

namespace App\Domain\Services;

use App\Domain\Repositories\UserRepositoryInterface;

class UserService
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function followUser(int $followerId, int $followedId): bool
    {
        if ($followerId === $followedId) {
            throw new \InvalidArgumentException('Los usuarios no pueden seguirse a sí mismos');
        }

        if ($this->userRepository->isFollowing($followerId, $followedId)) {
            throw new \InvalidArgumentException('Ya estas siguiendo a este usuario');
        }

        return $this->userRepository->addFollower($followerId, $followedId);
    }

    public function unfollowUser(int $followerId, int $followedId): bool
    {
        if (!$this->userRepository->isFollowing($followerId, $followedId)) {
            throw new \InvalidArgumentException('No estas siguiendo a este usuario');
        }

        return $this->userRepository->removeFollower($followerId, $followedId);
    }
}
