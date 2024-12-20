<?php

namespace App\Infrastructure\Controllers;

use App\Application\UseCases\CreateTweetUseCase;
use App\Application\UseCases\GetTimelineUseCase;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TweetController extends Controller
{
    private CreateTweetUseCase $createTweetUseCase;
    private GetTimelineUseCase $getTimelineUseCase;

    public function __construct(
        CreateTweetUseCase $createTweetUseCase,
        GetTimelineUseCase $getTimelineUseCase
    ) {
        $this->createTweetUseCase = $createTweetUseCase;
        $this->getTimelineUseCase = $getTimelineUseCase;
    }

    public function create(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|integer',
            'content' => 'required|string|max:280'
        ]);

        $tweet = $this->createTweetUseCase->execute(
            $request->input('user_id'),
            $request->input('content')
        );

        return response()->json([
            'id' => $tweet->getId(),
            'content' => $tweet->getContent(),
            'created_at' => $tweet->getCreatedAt()->format('c')
        ], 201);
    }

    public function timeline(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|integer'
        ]);

        $tweets = $this->getTimelineUseCase->execute($request->input('user_id'));

        return response()->json($tweets);
    }
}
