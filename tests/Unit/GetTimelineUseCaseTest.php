<?php

namespace Tests\Unit;

use App\Application\UseCases\GetTimelineUseCase;
use App\Domain\Services\TimelineService;
use PHPUnit\Framework\TestCase;

class GetTimelineUseCaseTest extends TestCase
{
    public function testGetTimelineForUser(): void
    {
        // Arrange
        /** @var TimelineService&\PHPUnit\Framework\MockObject\MockObject $timelineService */
        $timelineService = $this->getMockBuilder(TimelineService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $userId = 1;
        $expectedTimeline = [
            [
                'id' => 1,
                'content' => 'Test tweet 1',
                'created_at' => '2024-12-19 10:00:00'
            ],
            [
                'id' => 2,
                'content' => 'Test tweet 2',
                'created_at' => '2024-12-19 09:00:00'
            ]
        ];

        $timelineService->expects($this->once())
            ->method('generateTimelineForUser')
            ->with($this->equalTo($userId))
            ->willReturn($expectedTimeline);

        $useCase = new GetTimelineUseCase($timelineService);

        $result = $useCase->execute($userId);

        $this->assertEquals($expectedTimeline, $result);
    }
}
