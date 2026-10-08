<?php

namespace App\Services;

use Google\Client as GoogleClient;
use Illuminate\Support\Facades\Log;

class FcmService
{
    protected $client;

    public function __construct()
    {
        $this->client = new GoogleClient();
        
        // Use the newly configured service account
        $serviceAccountPath = storage_path('app/firebase/service-account.json');
        
        if (file_exists($serviceAccountPath)) {
            $this->client->setAuthConfig($serviceAccountPath);
            $this->client->addScope('https://www.googleapis.com/auth/firebase.messaging');
        } else {
            \Log::error('FCM Service Account not found at: ' . $serviceAccountPath);
        }
    }

    /**
     * Send a Firebase push notification using the HTTP v1 API.
     */
    public function sendNotification($fcmToken, $title, $body, $data = [], $imageUrl = null)
    {
        if (!$fcmToken) {
            \Log::warning('FCM: Attempted to send notification but no token provided.');
            return false;
        }

        if (!file_exists(storage_path('app/firebase/service-account.json'))) {
            \Log::error('FCM API Error: Service account JSON is missing at ' . storage_path('app/firebase/service-account.json') . '. Please generate it from Firebase Console and place it here.');
            return false;
        }

        try {
            $projectId = env('FCM_PROJECT_ID', 'velearn-c78a7');
            $url = 'https://fcm.googleapis.com/v1/projects/' . $projectId . '/messages:send';
            
            // Generate OAuth 2.0 access token
            $this->client->fetchAccessTokenWithAssertion();
            $accessToken = $this->client->getAccessToken();
            
            if (!$accessToken || !isset($accessToken['access_token'])) {
                \Log::error('FCM API Error: Could not generate OAuth token.');
                return false;
            }

            $token = $accessToken['access_token'];

            // Android configuration - No channel_id specified here so it uses the Android/Firebase default
            $androidConfig = [
                'notification' => [
                    'sound' => 'default'
                ]
            ];

            // If an image is provided, add it to the Android notification
            if ($imageUrl) {
                $androidConfig['notification']['image'] = $imageUrl;
            }

            // iOS configuration
            $apnsConfig = [
                'payload' => [
                    'aps' => [
                        'sound' => 'default',
                    ]
                ]
            ];

            // Build payload
            $payload = [
                'message' => [
                    'token' => $fcmToken,
                    'notification' => [
                        'title' => $title,
                        'body'  => $body,
                    ],
                    // Convert all data boolean/ints to string, FCM data accepts strings only
                    'data' => array_map('strval', $data),
                    'android' => $androidConfig,
                    'apns'    => $apnsConfig
                ]
            ];

            \Log::info('FCM Sending V1 Payload to: ' . $fcmToken, ['title' => $title]);

            // Execute HTTP Request
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
                'Content-Type: application/json',
            ]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

            $result = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            if ($curlError) {
                \Log::error('FCM V1 cURL Error: ' . $curlError);
                return false;
            }

            if ($httpCode >= 400) {
                \Log::error('FCM V1 Request Failed (Code ' . $httpCode . '): ' . $result);
                // Help debug common token errors
                if (strpos($result, 'NotRegistered') !== false || strpos($result, 'UNREGISTERED') !== false) {
                    \Log::info('FCM V1 Token expired or device unregistered: ' . $fcmToken);
                    // You might want to delete this token from the DB here
                }
                return false;
            }

            \Log::info('FCM V1 Message Sent Successfully.', ['response' => $result]);
            return true;

        } catch (\Exception $e) {
            \Log::error('FCM V1 Exception: ' . $e->getMessage());
            return false;
        }
    }
}
