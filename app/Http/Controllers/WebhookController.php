<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Spatie\Permission\Models\Role;
use App\Models\CallLog;
use App\Models\Staff;
use App\Models\User;

class WebhookController extends Controller
{
    private string $secretToken;

    public function __construct()
    {
        $this->secretToken = config('services.myoperator.webhook_secret');
    }

    public function handleWebhook(Request $request)
    {
        Log::info('Webhook Headers', $request->headers->all());
        Log::info('Webhook Body', $request->all());

        // ---------------- AUTH ----------------
        // MyOperator Webhook V2 uses company_id in payload, V1 uses Authorization header
        $companyId = config('services.myoperator.company_id');
        if ($request->filled('company_id')) {
            if ($request->input('company_id') !== $companyId) {
                Log::warning('Unauthorized webhook attempt: company_id mismatch');
                return response()->json(['message' => 'Unauthorized'], 401);
            }
        } else {
            $authHeader = trim((string) $request->header('Authorization'));
            if ($authHeader !== 'Bearer ' . trim($this->secretToken)) {
                Log::warning('Unauthorized webhook attempt', ['received' => $authHeader]);
                return response()->json(['message' => 'Unauthorized'], 401);
            }
        }

        // ---------------- POST ----------------
        if ($request->isMethod('post')) {
            $callData = $request->all();

            // Handle V2 Payload mapping
            if (isset($callData['event_type'])) {
                if ($callData['event_type'] !== 'call.summary') {
                    // Ignore intermediate events to avoid duplicate/incomplete records
                    return response()->json(['message' => 'Event ignored'], 200);
                }

                $payload = $callData['payload'] ?? [];
                
                $callData['_cr'] = $callData['customer_identifier'] ?? $payload['customer_number'] ?? '';
                $callData['_dr'] = $payload['duration'] ?? 0;
                $callData['_dn'] = $payload['department'] ?? null;
                $callData['_fu'] = $payload['recording_filename'] ?? null;
                $callData['_fn'] = $payload['recording_filename'] ?? null;
                
                $statusMap = [
                    'bridged' => 1,
                    'answered' => 1,
                    'missed' => 2,
                    'failed' => 2,
                    'voicemail' => 3,
                ];
                $statusString = strtolower($payload['status'] ?? '');
                $callData['_su'] = $statusMap[$statusString] ?? 1;

                $callData['_st'] = isset($payload['started_at']) ? strtotime($payload['started_at']) : time();
                $callData['_et'] = isset($payload['ended_at']) ? strtotime($payload['ended_at']) : time();
                
                $direction = strtolower($callData['direction'] ?? $payload['direction'] ?? 'incoming');
                $callData['_ev'] = ($direction === 'outgoing') ? 2 : 1;
            }

            $validator = Validator::make($callData, [
                '_cr' => 'required|string',
                '_dr' => 'required|numeric',
                '_dn' => 'nullable|string',
                '_fu' => 'nullable|string',
                '_su' => 'required|integer',
                '_st' => 'required|integer',
                '_et' => 'required|integer',
                '_ld' => 'nullable|array',
            ]);

            if ($validator->fails()) {
                Log::error('Validation failed', $validator->errors()->toArray());
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            try {
                $callerNumber = trim($callData['_cr']);

                // ---------------- IDENTIFY LEAD ----------------
                $cleanNo = preg_replace('/[^0-9]/', '', $callerNumber);
                $searchNo = strlen($cleanNo) > 10 ? substr($cleanNo, -10) : $cleanNo;

                $lead = \App\Models\Lead::where('phone', 'LIKE', "%$searchNo")
                    ->first();

                if (!$lead) {
                    // Attempt to extract name and email from various possible MyOperator payload fields
                    $contactName = $callData['customer_name'] ?? $callData['caller_name'] ?? ($callData['payload']['customer_name'] ?? ($callData['payload']['caller_name'] ?? 'NEW LEAD'));
                    $contactEmail = $callData['customer_email'] ?? $callData['caller_email'] ?? $callData['email'] ?? ($callData['payload']['customer_email'] ?? ($callData['payload']['caller_email'] ?? null));

                    $lead = \App\Models\Lead::create([
                        'name' => $contactName,
                        'phone' => $callerNumber,
                        'email' => $contactEmail,
                        'lead_source_id' => 1,
                        'lead_status_id' => 1,
                    ]);
                }

                $leadId = $lead->id;

                // Find last log to determine source/tag inheritance
                $existingLog = null;
                if ($leadId) {
                    $existingLog = CallLog::where('lead_id', $leadId)
                        ->orderByDesc('call_start')
                        ->first();
                }

                // Determine direction from MyOperator data based on official doc
                $eventType = (int)($callData['_ev'] ?? 1); // 1 = incoming, 2 = outgoing
                $direction = ($eventType == 2) ? 'Outgoing' : 'Incoming';
                
                // Check if missed status (2 = missed)
                if (($callData['_su'] ?? 0) == 2) {
                    $direction = 'Missed';
                }

                $callStart = Carbon::createFromTimestamp((int) $callData['_st']);
                $callEnd = Carbon::createFromTimestamp((int) $callData['_et']);

                // Extract staff_id from receiver info
                $staffId = null;
                $agentNumber = null;
                
                // Get department name (V2 might use department_name)
                $departmentName = $callData['_dn'] ?? null;
                if (isset($callData['event_type']) && isset($callData['payload'])) {
                    $departmentName = $callData['payload']['department_name'] ?? $callData['payload']['department'] ?? $departmentName;
                }

                // Try to find the agent who answered or received the call
                if (isset($callData['event_type']) && isset($callData['payload']['legs'])) {
                    $legs = $callData['payload']['legs'];
                    foreach ($legs as $leg) {
                        if (isset($leg['type']) && $leg['type'] === 'agent') {
                            // connected/answered agent
                            if (isset($leg['result']) && in_array($leg['result'], ['connected', 'answered'])) {
                                $agentNumber = $leg['phone_number'] ?? ($leg['agent']['contact'] ?? null);
                                if (empty($departmentName) && !empty($leg['department'])) {
                                    $departmentName = $leg['department'];
                                }
                                break;
                            }
                        }
                    }
                    if (!$agentNumber && count($legs) > 0) {
                        foreach ($legs as $leg) {
                            if (isset($leg['type']) && $leg['type'] === 'agent') {
                                $agentNumber = $leg['phone_number'] ?? ($leg['agent']['contact'] ?? null);
                                break;
                            }
                        }
                    }
                } elseif (isset($callData['event_type']) && isset($callData['payload']['receivers'])) {
                    $receivers = $callData['payload']['receivers'];
                    foreach ($receivers as $receiver) {
                        if (isset($receiver['status']) && $receiver['status'] === 'answered') {
                            $agentNumber = $receiver['number'] ?? null;
                            break;
                        }
                    }
                    if (!$agentNumber && count($receivers) > 0) {
                        $agentNumber = $receivers[0]['number'] ?? null;
                    }
                } elseif (isset($callData['_ld'])) {
                    $receivers = is_string($callData['_ld']) ? json_decode($callData['_ld'], true) : $callData['_ld'];
                    if (is_array($receivers)) {
                        foreach ($receivers as $receiver) {
                            if (isset($receiver['dial_status']) && $receiver['dial_status'] === 'answered') {
                                $agentNumber = $receiver['number'] ?? null;
                                break;
                            }
                        }
                        if (!$agentNumber && count($receivers) > 0) {
                            $agentNumber = $receivers[0]['number'] ?? null;
                        }
                    }
                }

                // Try to find the agent who answered via PHONE or EMAIL
                $agentEmail = null;
                if (isset($callData['event_type']) && isset($callData['payload']['legs'])) {
                    foreach ($callData['payload']['legs'] as $leg) {
                        if (isset($leg['type']) && $leg['type'] === 'agent' && isset($leg['agent']['email'])) {
                            $agentEmail = $leg['agent']['email'];
                            break;
                        }
                    }
                }

                if ($agentNumber) {
                    $cleanAgentNo = preg_replace('/[^0-9]/', '', $agentNumber);
                    $searchAgentNo = strlen($cleanAgentNo) > 10 ? substr($cleanAgentNo, -10) : $cleanAgentNo;

                    $staff = Staff::where('primary_phone', 'LIKE', "%$searchAgentNo%")
                        ->orWhere('secondary_phone', 'LIKE', "%$searchAgentNo%")
                        ->first();
                        
                    if ($staff) {
                        $staffId = $staff->id;
                    } else {
                        $user = User::where('phonenumber', 'LIKE', "%$searchAgentNo%")->first();
                        if ($user && $user->staff) {
                            $staffId = $user->staff->id;
                        }
                    }
                }

                // Fallback: match by agent email (most reliable — MyOperator always sends it)
                if (!$staffId && $agentEmail) {
                    $staff = Staff::where('email', $agentEmail)->first();
                    if ($staff) {
                        $staffId = $staff->id;
                    } else {
                        $user = User::where('email', $agentEmail)->first();
                        if ($user && $user->staff) {
                            $staffId = $user->staff->id;
                        }
                    }
                }

                Log::info("Staff resolved for call", ['agent_number' => $agentNumber, 'agent_email' => $agentEmail, 'staff_id' => $staffId]);

                // Update lead assignment if it is unassigned
                if ($staffId && empty($lead->assigned_to)) {
                    $lead->assigned_to = $staffId;
                    $lead->save();
                    Log::info("Lead #{$lead->id} assigned to staff #{$staffId}");
                }

                // ---------------- PREVENT DUPLICATES ----------------
                $duplicateLog = CallLog::where('caller_number', $callerNumber)
                    ->where('call_start', $callStart)
                    ->where('call_duration', $callData['_dr'])
                    ->first();

                if ($duplicateLog) {
                    Log::info("Duplicate call log prevented: {$callerNumber}", ['id' => $duplicateLog->id]);
                    return response()->json([
                        'message' => 'Duplicate call log prevented',
                        'call_log_id' => $duplicateLog->id
                    ], 200);
                }

                // ---------------- SYNC CONTACT ----------------
                $cleanContactNo = preg_replace('/[^0-9]/', '', $callerNumber);
                $searchContactNo = strlen($cleanContactNo) > 10 ? substr($cleanContactNo, -10) : $cleanContactNo;

                $contact = \App\Models\Contact::where('caller_number', 'LIKE', "%{$searchContactNo}")
                    ->orWhere('caller_number', $callerNumber)
                    ->first();

                if (!$contact) {
                    $contactName = $callData['customer_name'] ?? $callData['caller_name'] ?? ($callData['payload']['customer_name'] ?? ($callData['payload']['caller_name'] ?? $lead->name));
                    $contactEmail = $callData['customer_email'] ?? $callData['caller_email'] ?? ($callData['payload']['customer_email'] ?? ($callData['payload']['caller_email'] ?? $lead->email ?? null));

                    $contact = \App\Models\Contact::create([
                        'caller_number' => $callerNumber,
                        'name'          => $contactName,
                        'email'         => $contactEmail,
                        'source'        => 'MyOperator',
                    ]);
                }

                $contactId = $contact->id;

                // ---------------- CREATE CALL LOG ----------------
                $callLog = CallLog::create([
                    'caller_number' => $callerNumber,
                    'lead_id'       => $leadId,
                    'contact_id'    => $contactId,
                    'lead_tag'      => $existingLog->lead_tag ?? 'New Lead',
                    'staff_id'      => $staffId,
                    'call_duration' => $callData['_dr'],
                    'department'    => $departmentName,
                    'recording_url' => $callData['_fu'] ?? null,
                    'status'        => $callData['_su'],
                    'call_start'    => $callStart,
                    'call_end'      => $callEnd,
                    'full_log'      => json_encode($callData),
                    'direction'     => $direction,
                ]);

                Log::info("Call indexed: #{$callLog->id} from {$callerNumber} linked to Lead #{$leadId}", ['status' => $callData['_su']]);

                return response()->json([
                    'message' => 'Webhook stored successfully',
                    'call_log_id' => $callLog->id
                ], 201);

            } catch (\Throwable $e) {
                Log::error('DB Insert Failed', ['error' => $e->getMessage(), 'data' => $callData]);
                return response()->json(['message' => 'Database error'], 500);
            }
        }

        // ---------------- GET ----------------
        if ($request->isMethod('get')) {
            $caller = trim((string) $request->query('caller_number'));
            if (!$caller) {
                return response()->json(['message' => 'caller_number required'], 400);
            }

            $logs = CallLog::with('contact')
                ->where('caller_number', $caller)
                ->orderByDesc('call_start')
                ->get();

            if ($logs->isEmpty()) {
                return response()->json(['message' => 'No records found'], 404);
            }

            return response()->json($logs);
        }

        return response()->json(['message' => 'Method not allowed'], 405);
    }

    public function getRecording($id)
    {
        $log = CallLog::findOrFail($id);

        // 1️⃣ Check recording filename
        if (!$log->recording_url) {
            return response()->json([
                'message' => 'Recording not available'
            ], 404);
        }

        // Ensure full_log is properly decoded
        $fullLog = is_string($log->full_log) ? json_decode($log->full_log, true) : $log->full_log;
        
        // Sometimes it might still be a string if it was double-encoded
        if (is_string($fullLog)) {
            $fullLog = json_decode($fullLog, true);
        }

        $filename = $fullLog['_fn'] ?? null;
        if (!$filename) {
            \Log::error('MyOperator getRecording: Filename missing', ['id' => $id, 'fullLog' => $fullLog]);
            return response()->json([
                'message' => 'Filename missing from log data'
            ], 500);
        }

        $apiUrl = config('services.myoperator.base_url', 'https://publicapi.myoperator.co') . '/search/recordings/link';
        $apiToken = config('services.myoperator.api_key');

        \Log::info("Fetching MyOperator recording", ['url' => $apiUrl, 'file' => $filename, 'token_exists' => !empty($apiToken)]);

        // 2️⃣ Call MyOperator API with correct token + filename
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiToken,
            'x-api-key' => config('services.myoperator.x_api_key', '')
        ])->get($apiUrl, [
            'token' => $apiToken,
            'file'  => $filename
        ]);

        // 3️⃣ Handle failure
        if ($response->failed()) {
            \Log::error('MyOperator API failed', ['status' => $response->status(), 'body' => $response->body()]);
            return response()->json([
                'message' => 'Unable to fetch recording API'
            ], 500);
        }

        // 4️⃣ Validate response
        if ($response->json('status') !== 'success') {
            \Log::error('MyOperator API returned unsuccessful status', ['response' => $response->json()]);
            return response()->json([
                'message' => 'Recording link generation failed'
            ], 500);
        }

        return response()->json([
            'url' => $response->json('url')
        ]);
    }
}
