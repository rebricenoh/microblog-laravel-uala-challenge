<?php

namespace App\Domain\Services;

use App\Domain\Repositories\TweetRepositoryInterface;
use App\Domain\Repositories\UserRepositoryInterface;

class TimelineService
{
    private TweetRepositoryInterface $tweetRepository;
    private UserRepositoryInterface $userRepository;

    public function __construct(
        TweetRepositoryInterface $tweetRepository,
        UserRepositoryInterface $userRepository
    ) {
        $this->tweetRepository = $tweetRepository;
        $this->userRepository = $userRepository;
    }

    public function generateTimelineForUser(int $userId, int $limit = 50): array
    {
        $user = $this->userRepository->findById($userId);
        if (!$user) {
            throw new \InvalidArgumentException('Usuario no encontrado');
        }

        return $this->tweetRepository->getTimelineForUser($userId, $limit);
    }
}
