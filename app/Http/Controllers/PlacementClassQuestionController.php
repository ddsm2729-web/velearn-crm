<?php

namespace App\Http\Controllers;

use App\Models\PlacementClassQuestion;
use App\Models\PlacementClassOption;
use App\Models\PlacementClass;
use Illuminate\Http\Request;

class PlacementClassQuestionController extends Controller
{
    public function index(Request $request)
    {
        $classId = $request->query('class_id');
        $query = PlacementClassQuestion::with(['placementClass', 'options']);
        
        if ($classId) {
            $placementClass = PlacementClass::find($classId);
            if ($placementClass) {
                $dayClassIds = PlacementClass::where('day', $placementClass->day)->pluck('id');
                $query->whereIn('placement_class_id', $dayClassIds);
            } else {
                $query->where('placement_class_id', $classId);
            }
        }
        
        $questions = $query->latest('id')->get();
        return view('placement_class_questions.index', compact('questions', 'classId'));
    }

    public function create(Request $request)
    {
        $classes = PlacementClass::select('day', \DB::raw('MIN(id) as id'))->groupBy('day')->orderBy('day')->get();
        $selectedClassId = $request->query('class_id');
        return view('placement_class_questions.create', compact('classes', 'selectedClassId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'placement_class_id' => 'required|exists:placement_classes,id',
            'question' => 'required|string',
            'marks' => 'required|integer',
            'options' => 'required|array|min:2',
            'correct_option' => 'required|integer'
        ]);

        $question = PlacementClassQuestion::create([
            'placement_class_id' => $request->placement_class_id,
            'question' => $request->question,
            'marks' => $request->marks,
        ]);

        foreach ($request->options as $index => $optText) {
            if (!empty($optText)) {
                PlacementClassOption::create([
                    'placement_class_question_id' => $question->id,
                    'option' => $optText,
                    'is_correct' => ($index == $request->correct_option)
                ]);
            }
        }

        return redirect()->route('placement-class-questions.index', ['class_id' => $request->placement_class_id])
            ->with('success', 'Question created successfully.');
    }

    public function show($id)
    {
        $question = PlacementClassQuestion::with('options')->findOrFail($id);
        return view('placement_class_questions.show', compact('question'));
    }

    public function edit($id)
    {
        $question = PlacementClassQuestion::with('options')->findOrFail($id);
        $classes = PlacementClass::select('day', \DB::raw('MIN(id) as id'))->groupBy('day')->orderBy('day')->get();
        return view('placement_class_questions.edit', compact('question', 'classes'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'placement_class_id' => 'required|exists:placement_classes,id',
            'question' => 'required|string',
            'marks' => 'required|integer',
            'options' => 'required|array|min:2',
            'correct_option' => 'required|integer'
        ]);

        $question = PlacementClassQuestion::findOrFail($id);
        $question->update([
            'placement_class_id' => $request->placement_class_id,
            'question' => $request->question,
            'marks' => $request->marks,
        ]);

        // Recreate options
        $question->options()->delete();
        
        foreach ($request->options as $index => $optText) {
            if (!empty($optText)) {
                PlacementClassOption::create([
                    'placement_class_question_id' => $question->id,
                    'option' => $optText,
                    'is_correct' => ($index == $request->correct_option)
                ]);
            }
        }

        return redirect()->route('placement-class-questions.index', ['class_id' => $request->placement_class_id])
            ->with('success', 'Question updated successfully.');
    }

    public function destroy($id)
    {
        $question = PlacementClassQuestion::findOrFail($id);
        $classId = $question->placement_class_id;
        $question->delete();
        return redirect()->route('placement-class-questions.index', ['class_id' => $classId])
            ->with('success', 'Question deleted successfully.');
    }
}
