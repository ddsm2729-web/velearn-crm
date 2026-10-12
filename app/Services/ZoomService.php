<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ZoomService
{
    protected $accountId;
    protected $clientId;
    protected $clientSecret;
    protected $baseUrl;

    public function __construct()
    {
        $this->accountId = env('ZOOM_ACCOUNT_ID');
        $this->clientId = env('ZOOM_CLIENT_ID');
        $this->clientSecret = env('ZOOM_CLIENT_SECRET');
        $this->baseUrl = env('ZOOM_BASE_URL', 'https://api.zoom.us/v2/');
    }

    /**
     * Get the Server-to-Server OAuth Token
     */
    protected function getAccessToken()
    {
        try {
            $response = Http::withBasicAuth($this->clientId, $this->clientSecret)
                ->asForm()
                ->post('https://zoom.us/oauth/token', [
                    'grant_type' => 'account_credentials',
                    'account_id' => $this->accountId,
                ]);

            if ($response->successful()) {
                return $response->json('access_token');
            }

            Log::error('Zoom OAuth Error: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('Zoom OAuth Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Create a Zoom Meeting
     *
     * @param string $topic
     * @param string $startTime Format: YYYY-MM-DDTHH:MM:SS (e.g. 2024-05-15T10:00:00)
     * @param int $duration In minutes
     * @param string $timezone Default: Asia/Kolkata
     * @return array|null
     */
    protected static $invalidAlternativeHosts = [];

    public function createMeeting($topic, $startTime, $duration, $timezone = 'Asia/Kolkata', $alternativeHosts = '')
    {
        $token = $this->getAccessToken();

        if (!$token) {
            return null;
        }

        try {
            $payload = [
                'topic' => $topic,
                'type' => 2, // 2 = Scheduled Meeting
                'start_time' => $startTime,
                'duration' => $duration,
                'timezone' => $timezone,
                'settings' => [
                    'host_video' => true,
                    'participant_video' => true,
                    'join_before_host' => false,
                    'mute_upon_entry' => true,
                    'waiting_room' => true,
                ],
            ];

            // Do not globally assign all Technical Heads, as they must have licensed Zoom accounts in the same org.
            // This prevents Error 1114 loops from invalid emails.
            $existingHosts = empty($alternativeHosts) ? [] : explode(',', $alternativeHosts);
            
            // Filter out any hosts that we already know are invalid for this request lifecycle
            $validHosts = array_diff($existingHosts, self::$invalidAlternativeHosts);
            
            $combinedHosts = array_unique(array_filter($validHosts));
            $finalAlternativeHosts = implode(',', $combinedHosts);

            if (!empty($finalAlternativeHosts)) {
                $payload['settings']['alternative_hosts'] = $finalAlternativeHosts;
                $payload['settings']['alternative_hosts_email_notification'] = true;
            }

            $response = Http::withToken($token)
                ->post($this->baseUrl . 'users/me/meetings', $payload);

            if ($response->successful()) {
                return $response->json();
            }

            $errorBody = $response->json();
            
            // Code 1114 usually means the alternative host cannot be selected (e.g. not in the same account/unlicensed)
            // Or if it failed and we had alternative hosts set, let's retry without them so the meeting still gets created!
            if (isset($payload['settings']['alternative_hosts']) && isset($errorBody['code']) && $errorBody['code'] == 1114) {
                Log::warning('Zoom Alternative Host ('. $payload['settings']['alternative_hosts'] .') cannot be assigned. Retrying without alternative hosts...');
                
                // Remember that these hosts are invalid so we don't try them again in a loop
                $failedHosts = explode(',', $payload['settings']['alternative_hosts']);
                self::$invalidAlternativeHosts = array_merge(self::$invalidAlternativeHosts, $failedHosts);
                
            } else {
                Log::error('Zoom Create Meeting Error: ' . $response->body());
            }
            
            if (isset($payload['settings']['alternative_hosts'])) {
                Log::info('Retrying Zoom meeting creation without alternative_hosts...');
                unset($payload['settings']['alternative_hosts']);
                unset($payload['settings']['alternative_hosts_email_notification']);
                
                $retryResponse = Http::withToken($token)
                    ->post($this->baseUrl . 'users/me/meetings', $payload);
                    
                if ($retryResponse->successful()) {
                    return $retryResponse->json();
                }
                
                Log::error('Zoom Create Meeting Retry Error: ' . $retryResponse->body());
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Zoom Create Meeting Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Update a Zoom Meeting
     *
     * @param string $meetingId The Zoom Meeting ID
     * @param string $topic
     * @param string $startTime Format: YYYY-MM-DDTHH:MM:SS
     * @param int $duration In minutes
     * @param string $timezone Default: Asia/Kolkata
     * @return bool True if successfully updated, false otherwise
     */
    public function updateMeeting($meetingId, $topic, $startTime, $duration, $timezone = 'Asia/Kolkata')
    {
        $token = $this->getAccessToken();

        if (!$token || !$meetingId) {
            return false;
        }

        try {
            $response = Http::withToken($token)
                ->patch($this->baseUrl . 'meetings/' . $meetingId, [
                    'topic' => $topic,
                    'type' => 2, // Scheduled Meeting
                    'start_time' => $startTime,
                    'duration' => $duration,
                    'timezone' => $timezone,
                ]);

            if ($response->successful()) {
                return true;
            }

            Log::error('Zoom Update Meeting Error: ' . $response->body());
            return false;
        } catch (\Exception $e) {
            Log::error('Zoom Update Meeting Exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete a Zoom Meeting
     *
     * @param string $meetingId The Zoom Meeting ID
     * @return bool True if successfully deleted, false otherwise
     */
    public function deleteMeeting($meetingId)
    {
        $token = $this->getAccessToken();

        if (!$token || !$meetingId) {
            return false;
        }

        try {
            $response = Http::withToken($token)
                ->delete($this->baseUrl . 'meetings/' . $meetingId);

            if ($response->successful()) {
                return true;
            }

            Log::error('Zoom Delete Meeting Error: ' . $response->body());
            return false;
        } catch (\Exception $e) {
            Log::error('Zoom Delete Meeting Exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get details for a meeting
     *
     * @param string $meetingId
     * @return array|null
     */
    public function getMeetingDetails($meetingId)
    {
        $token = $this->getAccessToken();
        if (!$token || !$meetingId) return null;

        try {
            $response = Http::withToken($token)
                ->get($this->baseUrl . 'meetings/' . urlencode($meetingId));

            if ($response->successful()) {
                return $response->json();
            }

            Log::error("Zoom Get Meeting Details Error ({$meetingId}): " . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error("Zoom Get Meeting Details Exception ({$meetingId}): " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get past meeting details (including actual duration, start_time, end_time)
     * Refer: Zoom API GET /past_meetings/{meetingId}
     *
     * @param string $meetingId
     * @return array|null
     */
    public function getPastMeetingDetails($meetingId)
    {
        $token = $this->getAccessToken();
        if (!$token || !$meetingId) return null;

        try {
            $response = Http::withToken($token)
                ->get($this->baseUrl . 'past_meetings/' . urlencode($meetingId));

            if ($response->successful()) {
                return $response->json();
            }

            // Fallback to standard meeting details if past_meetings endpoint isn't ready
            return $this->getMeetingDetails($meetingId);
        } catch (\Exception $e) {
            Log::error("Zoom Get Past Meeting Details Exception ({$meetingId}): " . $e->getMessage());
            return $this->getMeetingDetails($meetingId);
        }
    }

    /**
     * Get meeting participants with pagination support
     * Refer: Zoom API GET /report/meetings/{meetingId}/participants
     * and fallback to GET /past_meetings/{meetingId}/participants
     *
     * @param string $meetingId
     * @return array List of participant records
     */
    public function getMeetingParticipants($meetingId)
    {
        $token = $this->getAccessToken();
        if (!$token || !$meetingId) {
            return [];
        }

        $allParticipants = [];
        $encodedMeetingId = urlencode($meetingId);

        // 1. First attempt: Report API (/report/meetings/{meetingId}/participants)
        $nextPageToken = null;
        $useReportEndpoint = true;

        do {
            try {
                $params = ['page_size' => 300];
                if (!empty($nextPageToken)) {
                    $params['next_page_token'] = $nextPageToken;
                }

                $response = Http::withToken($token)
                    ->get($this->baseUrl . 'report/meetings/' . $encodedMeetingId . '/participants', $params);

                if ($response->successful()) {
                    $data = $response->json();
                    $participants = $data['participants'] ?? [];
                    $allParticipants = array_merge($allParticipants, $participants);
                    $nextPageToken = $data['next_page_token'] ?? null;
                } else {
                    Log::warning("Zoom Report API participants returned status " . $response->status() . " for {$meetingId}: " . $response->body());
                    $useReportEndpoint = false;
                    break;
                }
            } catch (\Exception $e) {
                Log::error("Zoom Report API participants exception for {$meetingId}: " . $e->getMessage());
                $useReportEndpoint = false;
                break;
            }
        } while (!empty($nextPageToken));

        // If report endpoint succeeded and fetched participants, return them
        if ($useReportEndpoint && !empty($allParticipants)) {
            return $allParticipants;
        }

        // 2. Fallback attempt: Past Meetings API (/past_meetings/{meetingId}/participants)
        $nextPageToken = null;
        $pastParticipants = [];

        do {
            try {
                $params = ['page_size' => 300];
                if (!empty($nextPageToken)) {
                    $params['next_page_token'] = $nextPageToken;
                }

                $response = Http::withToken($token)
                    ->get($this->baseUrl . 'past_meetings/' . $encodedMeetingId . '/participants', $params);

                if ($response->successful()) {
                    $data = $response->json();
                    $participants = $data['participants'] ?? [];
                    $pastParticipants = array_merge($pastParticipants, $participants);
                    $nextPageToken = $data['next_page_token'] ?? null;
                } else {
                    Log::error("Zoom Past Meeting API participants failed for {$meetingId}: " . $response->body());
                    break;
                }
            } catch (\Exception $e) {
                Log::error("Zoom Past Meeting API participants exception for {$meetingId}: " . $e->getMessage());
                break;
            }
        } while (!empty($nextPageToken));

        return !empty($pastParticipants) ? $pastParticipants : $allParticipants;
    }

    /**
     * Validate Zoom Webhook Event URL (CRC Challenge)
     * Zoom requires endpoint to return hash_hmac('sha256', plainToken, secretToken)
     *
     * @param string $plainToken
     * @return array
     */
    public function generateWebhookTokenResponse($plainToken)
    {
        $secretToken = env('ZOOM_WEBHOOK_SECRET_TOKEN', env('ZOOM_CLIENT_SECRET'));
        $encryptedToken = hash_hmac('sha256', $plainToken, $secretToken);

        return [
            'plainToken' => $plainToken,
            'encryptedToken' => $encryptedToken,
        ];
    }
}

