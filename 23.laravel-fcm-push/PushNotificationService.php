<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Laravel\Firebase\Facades\Firebase;

class PushNotificationService
{
    /**
     * Send notification to users with FCM tokens
     *
     * @param array $userIds
     * @param string $title
     * @param string $body
     * @param array $data
     * @return array
     */
    public function sendNotificationToUsersWithFcm(array $userIds, string $title, string $body, array $data = []): array
    {
        $users = User::whereIn('id', $userIds)->get();
        $results = [
            'success' => [],
            'failed' => []
        ];

        foreach ($users as $user) {
            $tokens = $user->deviceTokens->pluck('token')->toArray();

            if (!empty($tokens)) {
                try {
                    $result = $this->sendToTokens($tokens, $title, $body, $data);

                    if ($result['success']) {
                        $results['success'][] = [
                            'user_id' => $user->id,
                            'tokens' => $result['tokens']
                        ];
                    } else {
                        $results['failed'][] = [
                            'user_id' => $user->id,
                            'error' => $result['error']
                        ];
                    }
                } catch (\Exception $e) {
                    Log::error('Push notification failed for user ' . $user->id, [
                        'error' => $e->getMessage(),
                        'user_id' => $user->id
                    ]);

                    $results['failed'][] = [
                        'user_id' => $user->id,
                        'error' => $e->getMessage()
                    ];
                }
            }
        }

        return $results;
    }

    /**
     * Send notification to specific FCM tokens
     *
     * @param array $tokens
     * @param string $title
     * @param string $body
     * @param array $data
     * @return array
     */
    public function sendToTokens(array $tokens, string $title, string $body, array $data = []): array
    {
        try {
            $messaging = Firebase::messaging();

            // Create notification
            $notification = Notification::create($title, $body);

            // Create message
            $message = CloudMessage::new()
                ->withNotification($notification)
                ->withData($data);

            // $message = CloudMessage::new()
            //     ->withData([
            //         'title' => $title,
            //         'body' => $body,
            //         // ... other custom keys
            //     ]);

            // Send to multiple tokens
            $report = $messaging->sendMulticast($message, $tokens);

            $successTokens = [];
            $failedTokens = [];

            foreach ($report->successes() as $success) {
                $successTokens[] = $success->target()->value();
            }

            foreach ($report->failures() as $failure) {
                $failedTokens[] = [
                    'token' => $failure->target()->value(),
                    'error' => $failure->error()->getMessage()
                ];
            }

            return [
                'success' => true,
                'success_count' => count($successTokens),
                'failure_count' => count($failedTokens),
                'tokens' => $successTokens,
                'failed_tokens' => $failedTokens
            ];
        } catch (\Exception $e) {
            Log::error('Firebase messaging error', [
                'error' => $e->getMessage(),
                'tokens_count' => count($tokens)
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Send notification to a topic
     *
     * @param string $topic
     * @param string $title
     * @param string $body
     * @param array $data
     * @return array
     */
    public function sendToTopic(string $topic, string $title, string $body, array $data = []): array
    {
        try {
            $messaging = Firebase::messaging();

            $notification = Notification::create($title, $body);

            $message = CloudMessage::withTarget('topic', $topic)
                ->withNotification($notification)
                ->withData($data);

            $messaging->send($message);

            return [
                'success' => true,
                'topic' => $topic
            ];
        } catch (\Exception $e) {
            Log::error('Firebase topic messaging error', [
                'error' => $e->getMessage(),
                'topic' => $topic
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Subscribe users to a topic
     *
     * @param array $userIds
     * @param string $topic
     * @return array
     */
    public function subscribeUsersToTopic(array $userIds, string $topic): array
    {
        $users = User::whereIn('id', $userIds)->get();
        $tokens = [];

        foreach ($users as $user) {
            $userTokens = $user->deviceTokens->pluck('token')->toArray();
            $tokens = array_merge($tokens, $userTokens);
        }

        if (empty($tokens)) {
            return [
                'success' => false,
                'error' => 'No tokens found for the specified users'
            ];
        }

        try {
            $messaging = Firebase::messaging();
            $report = $messaging->subscribeToTopic($topic, $tokens);

            return [
                'success' => true,
                'success_count' => $report->successes()->count(),
                'failure_count' => $report->failures()->count(),
                'topic' => $topic
            ];
        } catch (\Exception $e) {
            Log::error('Firebase topic subscription error', [
                'error' => $e->getMessage(),
                'topic' => $topic
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Unsubscribe users from a topic
     *
     * @param array $userIds
     * @param string $topic
     * @return array
     */
    public function unsubscribeUsersFromTopic(array $userIds, string $topic): array
    {
        $users = User::whereIn('id', $userIds)->get();
        $tokens = [];

        foreach ($users as $user) {
            $userTokens = $user->deviceTokens->pluck('token')->toArray();
            $tokens = array_merge($tokens, $userTokens);
        }

        if (empty($tokens)) {
            return [
                'success' => false,
                'error' => 'No tokens found for the specified users'
            ];
        }

        try {
            $messaging = Firebase::messaging();
            $report = $messaging->unsubscribeFromTopic($topic, $tokens);

            return [
                'success' => true,
                'success_count' => $report->successes()->count(),
                'failure_count' => $report->failures()->count(),
                'topic' => $topic
            ];
        } catch (\Exception $e) {
            Log::error('Firebase topic unsubscription error', [
                'error' => $e->getMessage(),
                'topic' => $topic
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}
