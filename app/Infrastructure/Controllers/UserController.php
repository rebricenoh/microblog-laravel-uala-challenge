<?php

namespace App\Infrastructure\Controllers;

use App\Application\UseCases\FollowUserUseCase;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private FollowUserUseCase $followUserUseCase;

    public function __construct(FollowUserUseCase $followUserUseCase)
    {
        $this->followUserUseCase = $followUserUseCase;
    }

    public function follow(Request $request): JsonResponse
    {
        $request->validate([
            'follower_id' => 'required|integer',
            'followed_id' => 'required|integer|different:follower_id'
        ]);

        try {
            $success = $this->followUserUseCase->execute(
                $request->input('follower_id'),
                $request->input('followed_id')
            );

            return response()->json([
                'success' => $success,
                'message' => $success ? 'Usuario seguido con éxito' : 'No se ha podido seguir al usuario'
            ], $success ? 200 : 400);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
