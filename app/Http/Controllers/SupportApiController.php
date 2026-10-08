<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupportTicket;
use Illuminate\Support\Str;

class SupportApiController extends Controller
{
    /**
     * Store a Technical Support session booking.
     */
    public function storeTechnical(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'date' => 'required|date',
                'slot' => 'required|string',
            ]);

            $userId = auth()->id() ?? $request->user_id;
            if (!$userId) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $ticket = SupportTicket::create([
                'user_id' => $userId,
                'reference_id' => 'TQ-' . strtoupper(Str::random(6)),
                'type' => 'technical',
                'title' => $request->title,
                'description' => $request->description,
                'preferred_date' => $request->date,
                'preferred_slot' => $request->slot,
                'status' => 'New',
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Technical support session booked successfully',
                'data' => $ticket
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a Non-Technical Support request.
     * File stored in public/uploads/support_attachments/ (same pattern as courses).
     */
    public function storeNonTechnical(Request $request)
    {
        try {
            $request->validate([
                'category' => 'required|string',
                'subject' => 'required|string|max:255',
                'description' => 'required|string',
                'attachment' => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf|max:5120',
            ]);

            $userId = auth()->id() ?? $request->user_id;
            if (!$userId) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            /* ---------- ATTACHMENT ---------- */
            $attachmentName = null;
            if ($request->hasFile('attachment')) {
                $uploadDir = public_path('uploads/support_attachments');

                // Auto-create directory if it doesn't exist
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                $file = $request->file('attachment');
                $attachmentName = time() . '_' . uniqid() . '.' . $file->extension();
                $file->move($uploadDir, $attachmentName);
            }

            $ticket = SupportTicket::create([
                'user_id' => $userId,
                'reference_id' => 'NTQ-' . strtoupper(Str::random(6)),
                'type' => 'non-technical',
                'category' => $request->category,
                'subject' => $request->subject,
                'description' => $request->description,
                'attachment' => $attachmentName,  // just filename, like courses store image
                'status' => 'New',
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Non-technical support request submitted successfully',
                'data' => $ticket
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get 5 most recent support tickets for the authenticated user.
     * Appends full attachment_url for file access.
     */
    public function getRecent()
    {
        try {
            $tickets = SupportTicket::where('user_id', auth()->id())
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get()
                ->map(function ($ticket) {
                    // Append a publicly accessible URL for the attachment
                    $ticket->attachment_url = $ticket->attachment
                        ? url('uploads/support_attachments/' . $ticket->attachment)
                        : null;
                    return $ticket;
                });

            return response()->json([
                'status' => true,
                'data' => $tickets
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get booked slots for a specific date.
     */
    public function getBookedSlots(Request $request)
    {
        try {
            $date = $request->get('date');
            if (!$date) {
                return response()->json(['status' => false, 'message' => 'Date is required'], 400);
            }

            // Find all slots booked on this date that are active
            $bookedSlots = SupportTicket::where('preferred_date', $date)
                ->where('type', 'technical')
                ->whereNotIn('status', ['Resolved', 'Cancelled'])
                ->pluck('preferred_slot');

            return response()->json([
                'status' => true,
                'data' => $bookedSlots
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
