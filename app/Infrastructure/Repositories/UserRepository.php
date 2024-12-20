<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\User;
use App\Domain\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;

class UserRepository implements UserRepositoryInterface
{
    public function findById(int $id): ?User
    {
        $userData = DB::table('users')->find($id);

        if (!$userData) {
            return null;
        }

        $user = new User($userData->name);
        return $user;
    }

    public function save(User $user): User
    {
        $id = DB::table('users')->insertGetId([
            'name' => $user->getName(),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return $user;
    }

    public function addFollower(int $followerId, int $followedId): bool
    {
        try {
            DB::table('follows')->insert([
                'follower_id' => $followerId,
                'followed_id' => $followedId,
                'created_at' => now()
            ]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function removeFollower(int $followerId, int $followedId): bool
    {
        return DB::table('follows')
            ->where('follower_id', $followerId)
            ->where('followed_id', $followedId)
            ->delete() > 0;
    }

    public function isFollowing(int $followerId, int $followedId): bool
    {
        return DB::table('follows')
            ->where('follower_id', $followerId)
            ->where('followed_id', $followedId)
            ->exists();
    }
}
