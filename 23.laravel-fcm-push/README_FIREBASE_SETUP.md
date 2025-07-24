# Firebase Push Notification Setup Guide

## Overview

This guide will help you set up Firebase Cloud Messaging (FCM) for push notifications using the official Firebase Admin SDK.

## Prerequisites

-   Firebase project with Cloud Messaging enabled
-   Service account credentials from Firebase Console

## Step 1: Get Firebase Service Account Credentials

1. Go to [Firebase Console](https://console.firebase.google.com/)
2. Select your project
3. Go to Project Settings (gear icon)
4. Go to Service Accounts tab
5. Click "Generate new private key"
6. Download the JSON file
7. Place the JSON file in your Laravel project (e.g., `storage/app/firebase/service-account.json`)

## Step 2: Environment Configuration

Add the following to your `.env` file:

```env
# Firebase Configuration
FIREBASE_PROJECT=your-project-id
FIREBASE_CREDENTIALS=storage/app/firebase/service-account.json
FIREBASE_DATABASE_URL=https://your-project-id.firebaseio.com
```

## Step 3: Update Service Provider

Add the Firebase service provider to `config/app.php` in the providers array:

```php
'providers' => ServiceProvider::defaultProviders()->merge([
    // ... other providers
    Kreait\Laravel\Firebase\ServiceProvider::class,
])->toArray(),
```

## Step 4: Usage Examples

### Basic Usage

```php
use App\Services\PushNotificationService;

$pushService = new PushNotificationService();

// Send to specific users
$userIds = [1, 2, 3];
$result = $pushService->sendNotificationToUsersWithFcm(
    $userIds,
    'Notification Title',
    'Notification Body',
    ['custom_data' => 'value']
);

// Send to specific tokens
$tokens = ['token1', 'token2'];
$result = $pushService->sendToTokens(
    $tokens,
    'Notification Title',
    'Notification Body',
    ['custom_data' => 'value']
);

// Send to topic
$result = $pushService->sendToTopic(
    'news',
    'Breaking News',
    'Check out the latest updates!',
    ['url' => 'https://example.com/news']
);
```

### Topic Management

```php
// Subscribe users to a topic
$userIds = [1, 2, 3];
$result = $pushService->subscribeUsersToTopic($userIds, 'news');

// Unsubscribe users from a topic
$result = $pushService->unsubscribeUsersFromTopic($userIds, 'news');
```

## Step 5: Frontend Integration

Update your frontend JavaScript to handle FCM:

```javascript
// Initialize Firebase (already in your master.blade.php)
const messaging = firebase.messaging();

// Request permission
messaging
    .requestPermission()
    .then(function () {
        console.log("Notification permission granted.");
        return messaging.getToken();
    })
    .then(function (token) {
        // Send token to your Laravel backend
        sendTokenToServer(token);
    })
    .catch(function (err) {
        console.log("Unable to get permission to notify.", err);
    });

// Handle incoming messages
messaging.onMessage((payload) => {
    console.log("Message received:", payload);
    // Handle the notification
});

// Handle background messages
messaging.onBackgroundMessage((payload) => {
    console.log("Background message received:", payload);
    // Handle background notification
});
```

## Step 6: Token Management

Create an API endpoint to save FCM tokens:

```php
// In your API controller
public function saveToken(Request $request)
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

    return response()->json(['success' => true]);
}
```

## Step 7: Testing

Test your implementation:

```php
// In a controller or command
$pushService = new PushNotificationService();

// Test with a single user
$result = $pushService->sendNotificationToUsersWithFcm(
    [auth()->id()],
    'Test Notification',
    'This is a test notification'
);

dd($result);
```

## Troubleshooting

### Common Issues

1. **Credentials not found**: Make sure the service account JSON file path is correct
2. **Permission denied**: Ensure your service account has the necessary permissions
3. **Invalid token**: Tokens can expire, implement token refresh logic
4. **Topic not found**: Topics must be created before subscribing users

### Debug Mode

Enable Firebase logging in your `.env`:

```env
FIREBASE_HTTP_LOG_CHANNEL=stack
FIREBASE_HTTP_DEBUG_LOG_CHANNEL=stack
```

## Migration from Old Package

The new implementation is backward compatible with your existing code. The main changes are:

1. **Return values**: The new service returns detailed results instead of void
2. **Error handling**: Better error handling and logging
3. **Additional features**: Topic management and detailed reporting

## Security Considerations

1. **Service Account**: Keep your service account JSON file secure
2. **Token Validation**: Validate tokens on the server side
3. **Rate Limiting**: Implement rate limiting for notification sending
4. **User Consent**: Always request user permission before sending notifications

## Performance Tips

1. **Batch sending**: Use `sendMulticast` for multiple tokens
2. **Token cleanup**: Remove invalid tokens regularly
3. **Caching**: Cache Firebase configuration
4. **Async processing**: Use queues for large notification batches

## Support

For issues with the Firebase Admin SDK, refer to:

-   [Firebase Admin SDK Documentation](https://firebase.google.com/docs/admin/setup)
-   [Kreait Firebase PHP](https://github.com/kreait/firebase-php)
-   [Laravel Firebase Package](https://github.com/kreait/laravel-firebase)
