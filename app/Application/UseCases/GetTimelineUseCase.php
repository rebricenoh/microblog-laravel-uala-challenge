<?php

namespace App\Application\UseCases;

use App\Domain\Services\TimelineService;

class GetTimelineUseCase
{
    private TimelineService $timelineService;

    public function __construct(TimelineService $timelineService)
    {
        $this->timelineService = $timelineService;
    }

    public function execute(int $userId, int $limit = 50): array
    {
        return $this->timelineService->generateTimelineForUser($userId, $limit);
    }
}
