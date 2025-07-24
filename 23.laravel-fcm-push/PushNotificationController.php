<?php

namespace App\Http\Controllers;

use App\Services\PushNotificationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PushNotificationController extends Controller
{
    protected $pushService;

    public function __construct(PushNotificationService $pushService)
    {
        $this->pushService = $pushService;
    }

    /**
     * Send notification to specific users
     */
    public function sendToUsers(Request $request): JsonResponse
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'integer|exists:users,id',
            'title' => 'required|string|max:255',
            'body' => 'required|string|max:1000',
            'data' => 'array'
        ]);

        $result = $this->pushService->sendNotificationToUsersWithFcm(
            $request->user_ids,
            $request->title,
            $request->body,
            $request->data ?? []
        );

        return response()->json([
            'success' => !empty($result['success']),
            'data' => $result
        ]);
    }

    /**
     * Send notification to a topic
     */
    public function sendToTopic(Request $request): JsonResponse
    {
        $request->validate([
            'topic' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'body' => 'required|string|max:1000',
            'data' => 'array'
        ]);

        $result = $this->pushService->sendToTopic(
            $request->topic,
            $request->title,
            $request->body,
            $request->data ?? []
        );

        return response()->json([
            'success' => $result['success'],
            'data' => $result
        ]);
    }

    /**
     * Subscribe users to a topic
     */
    public function subscribeToTopic(Request $request): JsonResponse
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'integer|exists:users,id',
            'topic' => 'required|string|max:255'
        ]);

        $result = $this->pushService->subscribeUsersToTopic(
            $request->user_ids,
            $request->topic
        );

        return response()->json([
            'success' => $result['success'],
            'data' => $result
        ]);
    }

    /**
     * Unsubscribe users from a topic
     */
    public function unsubscribeFromTopic(Request $request): JsonResponse
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'integer|exists:users,id',
            'topic' => 'required|string|max:255'
        ]);

        $result = $this->pushService->unsubscribeUsersFromTopic(
            $request->user_ids,
            $request->topic
        );

        return response()->json([
            'success' => $result['success'],
            'data' => $result
        ]);
    }

    /**
     * Save FCM token for the authenticated user
     */
    public function saveToken(Request $request): JsonResponse
    {
        $request->validate([
            'token' => 'required|string'
        ]);

        $user = auth()->user();

        // Save or update the token
        $user->deviceTokens()->updateOrCreate(
            ['token' => $request->token],
            ['token' => $request->token]
        );

        return response()->json([
            'success' => true,
            'message' => 'Token saved successfully'
        ]);
    }

    /**
     * Remove FCM token for the authenticated user
     */
    public function removeToken(Request $request): JsonResponse
    {
        $request->validate([
            'token' => 'required|string'
        ]);

        $user = auth()->user();

        $user->deviceTokens()->where('token', $request->token)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Token removed successfully'
        ]);
    }
}
