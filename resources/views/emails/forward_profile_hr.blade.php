<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subjectText }}</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f1f5f9; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f1f5f9; padding: 30px 10px;">
        <tr>
            <td align="center">
                <table border="0" cellpadding="0" cellspacing="0" width="620" style="background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 16px rgba(15, 23, 42, 0.08); border: 1px solid #e2e8f0;">
                    <!-- Header -->
                    <tr>
                        <td align="center" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); padding: 32px 24px; border-bottom: 3px solid #06b6d4;">
                            <img src="https://velearn.in/assets/images/velearn-logo.png" alt="VeLearn Logo" style="max-height: 48px; margin-bottom: 12px; display: block; filter: brightness(0) invert(1);">
                            <h1 style="color: #ffffff; margin: 0; font-size: 20px; font-weight: 700; letter-spacing: 0.5px;">Shortlisted Candidate Recommendation</h1>
                            <p style="color: #94a3b8; margin: 6px 0 0 0; font-size: 13px;">VeLearn Corporate Placement Cell</p>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 32px 32px 24px 32px;">
                            <p style="color: #334155; margin: 0 0 16px 0; font-size: 15px; font-weight: 600;">Dear Hiring Team,</p>
                            
                            @if(!empty($remarks))
                                <div style="background-color: #f8fafc; border-left: 4px solid #06b6d4; padding: 14px 18px; margin-bottom: 24px; border-radius: 0 8px 8px 0;">
                                    <p style="color: #475569; margin: 0; font-size: 14px; line-height: 1.6; white-space: pre-line;">{!! nl2br(e($remarks)) !!}</p>
                                </div>
                            @else
                                <p style="color: #475569; margin: 0 0 20px 0; font-size: 14px; line-height: 1.6;">
                                    We are pleased to share the profile of a qualified and verified candidate from VeLearn for your review for the <strong>{{ $application->opening->job_role ?? 'open' }}</strong> position at <strong>{{ $application->opening->company->company_name ?? 'your organization' }}</strong>.
                                </p>
                            @endif

                            @php
                                $user = $application->user;
                                $profile = $user->placementProfile ?? null;
                                $cu = $application->courseUser;
                                
                                // Format resume URL
                                $resumeUrl = null;
                                if ($profile && $profile->upload_resume) {
                                    $resumeUrl = str_starts_with($profile->upload_resume, 'http') 
                                        ? $profile->upload_resume 
                                        : asset($profile->upload_resume);
                                }
                                
                                // Format portfolio URL
                                $portfolioUrl = $profile->portfolio_url ?? null;
                                
                                // Format GitHub / project URL
                                $githubUrl = $profile->github_url ?? null;
                                if (!$githubUrl && $profile && $profile->upload_project_docs) {
                                    $githubUrl = str_starts_with($profile->upload_project_docs, 'http') 
                                        ? $profile->upload_project_docs 
                                        : asset($profile->upload_project_docs);
                                }
                            @endphp

                            <!-- Candidate Details Card -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; margin-bottom: 24px; border-collapse: separate; overflow: hidden;">
                                <tr>
                                    <td colspan="2" style="background-color: #f8fafc; padding: 12px 18px; border-bottom: 1px solid #e2e8f0;">
                                        <strong style="color: #1e293b; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px;">Candidate Information</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 18px; color: #64748b; font-size: 13px; width: 35%; border-bottom: 1px solid #f1f5f9;">Candidate Name</td>
                                    <td style="padding: 12px 18px; color: #0f172a; font-size: 13px; font-weight: 600; border-bottom: 1px solid #f1f5f9;">{{ $user->name ?? 'Candidate' }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 18px; color: #64748b; font-size: 13px; border-bottom: 1px solid #f1f5f9;">Contact Email</td>
                                    <td style="padding: 12px 18px; color: #0f172a; font-size: 13px; border-bottom: 1px solid #f1f5f9;">
                                        <a href="mailto:{{ $user->email }}" style="color: #0284c7; text-decoration: none;">{{ $user->email ?? '—' }}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 18px; color: #64748b; font-size: 13px; border-bottom: 1px solid #f1f5f9;">Contact Phone</td>
                                    <td style="padding: 12px 18px; color: #0f172a; font-size: 13px; border-bottom: 1px solid #f1f5f9;">{{ $user->phonenumber ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 18px; color: #64748b; font-size: 13px; border-bottom: 1px solid #f1f5f9;">Course Completed</td>
                                    <td style="padding: 12px 18px; color: #0f172a; font-size: 13px; border-bottom: 1px solid #f1f5f9;">{{ $cu->course->title ?? 'Professional Training' }} (Batch: {{ $cu->batch->batch_code ?? 'Regular' }})</td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 18px; color: #64748b; font-size: 13px;">Applying For</td>
                                    <td style="padding: 12px 18px; color: #0f172a; font-size: 13px; font-weight: 600;">{{ $application->opening->job_role ?? 'Open Role' }}</td>
                                </tr>
                            </table>

                            <!-- Profile Action Buttons -->
                            <div style="margin-bottom: 24px; text-align: center;">
                                <p style="color: #64748b; font-size: 12px; margin: 0 0 12px 0; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px;">Candidate Verification & Portfolios</p>
                                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                    <tr>
                                        <td align="center" style="display: flex; justify-content: center; gap: 10px; flex-wrap: wrap;">
                                            @if(($linkType === 'combined' || $linkType === 'resume') && $resumeUrl)
                                                <a href="{{ $resumeUrl }}" target="_blank" style="display: inline-block; background-color: #0284c7; color: #ffffff; text-decoration: none; padding: 10px 20px; font-size: 13px; font-weight: 600; border-radius: 6px; margin: 4px;">
                                                    📄 View Resume
                                                </a>
                                            @endif

                                            @if(($linkType === 'combined' || $linkType === 'portfolio') && $portfolioUrl)
                                                <a href="{{ $portfolioUrl }}" target="_blank" style="display: inline-block; background-color: #0d9488; color: #ffffff; text-decoration: none; padding: 10px 20px; font-size: 13px; font-weight: 600; border-radius: 6px; margin: 4px;">
                                                    🌐 View Portfolio
                                                </a>
                                            @endif

                                            @if(($linkType === 'combined' || $linkType === 'project') && $githubUrl)
                                                <a href="{{ $githubUrl }}" target="_blank" style="display: inline-block; background-color: #1e293b; color: #ffffff; text-decoration: none; padding: 10px 20px; font-size: 13px; font-weight: 600; border-radius: 6px; margin: 4px;">
                                                    💻 GitHub / Projects
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <p style="color: #475569; margin: 0 0 24px 0; font-size: 13px; line-height: 1.6;">
                                Please review this candidate's profile and feel free to revert back to schedule an interview or request additional candidate information.
                            </p>
                            
                            <p style="color: #334155; margin: 0; font-size: 14px;">
                                Warm regards,<br>
                                <strong style="color: #0f172a;">Placement & Career Relations Team</strong><br>
                                <span style="color: #64748b; font-size: 12px;">VeLearn IT Solutions &middot; digital@velearn.in</span>
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td align="center" style="background-color: #f8fafc; padding: 20px; border-top: 1px solid #e2e8f0;">
                            <p style="color: #94a3b8; margin: 0 0 6px 0; font-size: 12px;">© {{ date('Y') }} VeLearn IT Solutions. All rights reserved.</p>
                            <a href="https://velearn.in" style="color: #0284c7; font-size: 12px; text-decoration: none; font-weight: 600;">Visit VeLearn Portal</a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
