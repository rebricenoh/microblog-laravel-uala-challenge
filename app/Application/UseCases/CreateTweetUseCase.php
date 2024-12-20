<?php

namespace App\Application\UseCases;

use App\Domain\Entities\Tweet;
use App\Domain\Repositories\TweetRepositoryInterface;

class CreateTweetUseCase
{
    private TweetRepositoryInterface $tweetRepository;

    public function __construct(TweetRepositoryInterface $tweetRepository)
    {
        $this->tweetRepository = $tweetRepository;
    }

    public function execute(int $userId, string $content): Tweet
    {
        if (strlen($content) > 280) {
            throw new \InvalidArgumentException('El contenido del tweet no puede superar los 280 caracteres');
        }

        $tweet = new Tweet($userId, $content);
        return $this->tweetRepository->save($tweet);
    }
}
