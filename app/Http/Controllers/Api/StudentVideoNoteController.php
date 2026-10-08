<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\StudentVideoNote;
use Illuminate\Http\Request;

class StudentVideoNoteController extends Controller
{
    public function index($student_id, $log_id)
    {
        $notes = StudentVideoNote::where('student_id', $student_id)
            ->where('batch_topic_log_id', $log_id)
            ->orderBy('created_at', 'desc')
            ->get();
            
        return response()->json(['status' => true, 'notes' => $notes]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
            'batch_topic_log_id' => 'required|exists:batch_topic_logs,id',
            'title' => 'nullable|string|max:255',
            'content' => 'required|string',
        ]);

        $note = StudentVideoNote::create($request->all());

        return response()->json(['status' => true, 'message' => 'Note created successfully', 'note' => $note]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'content' => 'required|string',
        ]);

        $note = StudentVideoNote::find($id);
        if (!$note) {
            return response()->json(['status' => false, 'message' => 'Note not found'], 404);
        }

        $note->update($request->only(['title', 'content']));

        return response()->json(['status' => true, 'message' => 'Note updated successfully', 'note' => $note]);
    }

    public function destroy($id)
    {
        $note = StudentVideoNote::find($id);
        if ($note) {
            $note->delete();
        }
        return response()->json(['status' => true, 'message' => 'Note deleted successfully']);
    }
}
