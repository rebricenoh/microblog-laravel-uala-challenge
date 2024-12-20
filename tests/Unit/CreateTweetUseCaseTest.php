<?php

namespace Tests\Unit;

use App\Application\UseCases\CreateTweetUseCase;
use App\Domain\Entities\Tweet;
use App\Domain\Repositories\TweetRepositoryInterface;
use PHPUnit\Framework\TestCase;

class CreateTweetUseCaseTest extends TestCase
{
    public function testCreateValidTweet(): void
    {
        /** @var TweetRepositoryInterface&\PHPUnit\Framework\MockObject\MockObject $tweetRepository */
        $tweetRepository = $this->getMockBuilder(TweetRepositoryInterface::class)
            ->getMock();

        $userId = 1;
        $content = "Tweet de prueba";
        $tweet = new Tweet($userId, $content);

        $tweetRepository->expects($this->once())
            ->method('save')
            ->willReturn($tweet);

        $useCase = new CreateTweetUseCase($tweetRepository);

        $result = $useCase->execute($userId, $content);

        $this->assertInstanceOf(Tweet::class, $result);
        $this->assertEquals($content, $result->getContent());
        $this->assertEquals($userId, $result->getUserId());
    }

    public function testCreateTweetWithTooLongContent(): void
    {
        /** @var TweetRepositoryInterface&\PHPUnit\Framework\MockObject\MockObject $tweetRepository */
        $tweetRepository = $this->getMockBuilder(TweetRepositoryInterface::class)
            ->getMock();

        $useCase = new CreateTweetUseCase($tweetRepository);

        $userId = 1;
        $content = str_repeat("a", 281);

        $this->expectException(\InvalidArgumentException::class);

        $useCase->execute($userId, $content);
    }
}
