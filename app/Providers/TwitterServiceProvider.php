<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\Repositories\TweetRepositoryInterface;
use App\Infrastructure\Repositories\EloquentTweetRepository;

class TwitterServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(TweetRepositoryInterface::class, EloquentTweetRepository::class);
    }
}
