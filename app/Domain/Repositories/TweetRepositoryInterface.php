<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Tweet;

interface TweetRepositoryInterface
{
    public function save(Tweet $tweet): Tweet;
    public function getTimelineForUser(int $userId, int $limit = 50): array;
}
