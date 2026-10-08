<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlacementProfile extends Model
{
    protected $fillable = [
        'user_id',
        'upload_resume',
        'resume_status',
        'resume_feedback',
        'portfolio_url',
        'portfolio_status',
        'portfolio_feedback',
        'github_url',
        'github_status',
        'github_feedback',
        'upload_project_docs',
        'project_docs_status',
        'project_docs_feedback',
        'overall_status',
        'reviewed_at',
        'reviewed_by',
    ];
}
