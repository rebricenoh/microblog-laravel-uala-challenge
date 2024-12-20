<?php

namespace App\Providers;

use App\Domain\Repositories\TweetRepositoryInterface;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Infrastructure\Repositories\TweetRepository;
use App\Infrastructure\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(TweetRepositoryInterface::class, TweetRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }
}
