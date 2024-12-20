<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\User;

interface UserRepositoryInterface
{
    public function findById(int $id): ?User;
    public function save(User $user): User;
    public function addFollower(int $followerId, int $followedId): bool;
    public function removeFollower(int $followerId, int $followedId): bool;
    public function isFollowing(int $followerId, int $followedId): bool;
}
