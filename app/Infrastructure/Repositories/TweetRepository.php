<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\Tweet;
use App\Domain\Repositories\TweetRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class TweetRepository implements TweetRepositoryInterface
{
    private const CACHE_TTL = 3600;

    public function save(Tweet $tweet): Tweet
    {
        $id = DB::table('tweets')->insertGetId([
            'user_id' => $tweet->getUserId(),
            'content' => $tweet->getContent(),
            'created_at' => $tweet->getCreatedAt()
        ]);

        // Asignar el ID al objeto Tweet
        $tweet->setId($id);

        // Invalidate timeline cache for followers
        $followers = DB::table('follows')
            ->where('followed_id', $tweet->getUserId())
            ->pluck('follower_id');

        foreach ($followers as $followerId) {
            Redis::del($this->getTimelineCacheKey($followerId));
        }

        return $tweet;
    }

    public function getTimelineForUser(int $userId, int $limit = 50): array
    {
        $cacheKey = $this->getTimelineCacheKey($userId);

        if (Redis::exists($cacheKey)) {
            return json_decode(Redis::get($cacheKey), true);
        }

        $tweets = DB::table('tweets')
            ->join('follows', 'tweets.user_id', '=', 'follows.followed_id')
            ->where('follows.follower_id', $userId)
            ->orderBy('tweets.created_at', 'desc')
            ->limit($limit)
            ->get(['tweets.*'])
            ->toArray();

        Redis::setex($cacheKey, self::CACHE_TTL, json_encode($tweets));

        return $tweets;
    }

    private function getTimelineCacheKey(int $userId): string
    {
        return "timeline:{$userId}";
    }
}
