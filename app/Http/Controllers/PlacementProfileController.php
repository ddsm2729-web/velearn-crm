<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PlacementProfileController extends Controller
{
    public function getProfile($user_id)
    {
        $profile = \App\Models\PlacementProfile::firstOrCreate(
            ['user_id' => $user_id]
        );
        return response()->json(['success' => true, 'profile' => $profile]);
    }

    public function uploadResume(Request $request, $user_id)
    {
        $profile = \App\Models\PlacementProfile::firstOrCreate(['user_id' => $user_id]);
        
        if ($request->hasFile('resume')) {
            $path = $request->file('resume')->store('placement_docs', 'public');
            $profile->upload_resume = $path;
            $profile->resume_status = 'pending';
            $profile->save();
            $this->updateOverallStatus($profile);
            return response()->json(['success' => true, 'path' => $path]);
        }
        return response()->json(['success' => false, 'message' => 'No file uploaded'], 400);
    }

    public function updatePortfolio(Request $request, $user_id)
    {
        $profile = \App\Models\PlacementProfile::firstOrCreate(['user_id' => $user_id]);
        
        if ($request->has('portfolio_url')) {
            $profile->portfolio_url = $request->portfolio_url;
            $profile->portfolio_status = 'pending';
            $profile->save();
            $this->updateOverallStatus($profile);
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => 'No URL provided'], 400);
    }

    public function uploadProjectDocs(Request $request, $user_id)
    {
        $profile = \App\Models\PlacementProfile::firstOrCreate(['user_id' => $user_id]);
        
        if ($request->hasFile('project_docs')) {
            $path = $request->file('project_docs')->store('placement_docs', 'public');
            $profile->upload_project_docs = $path;
            $profile->project_docs_status = 'pending';
            $profile->save();
            $this->updateOverallStatus($profile);
            return response()->json(['success' => true, 'path' => $path]);
        }
        return response()->json(['success' => false, 'message' => 'No file uploaded'], 400);
    }

    public function updateGithub(Request $request, $user_id)
    {
        $profile = \App\Models\PlacementProfile::firstOrCreate(['user_id' => $user_id]);
        
        if ($request->has('github_url')) {
            $profile->github_url = $request->github_url;
            $profile->github_status = 'pending';
            $profile->save();
            $this->updateOverallStatus($profile);
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => 'No URL provided'], 400);
    }

    private function updateOverallStatus($profile)
    {
        // Simple logic: if everything is verified, set overall to all_verified
        if ($profile->resume_status === 'verified' && 
            $profile->portfolio_status === 'verified' && 
            $profile->project_docs_status === 'verified' &&
            $profile->github_status === 'verified') {
            $profile->overall_status = 'all_verified';
        } else {
            $profile->overall_status = 'pending';
        }
        $profile->save();
    }
}
