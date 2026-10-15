<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interview Call Letter - VeLearn</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f1f5f9; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f1f5f9; padding: 30px 10px;">
        <tr>
            <td align="center">
                <table border="0" cellpadding="0" cellspacing="0" width="620" style="background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 16px rgba(15, 23, 42, 0.08); border: 1px solid #e2e8f0;">
                    <!-- Header -->
                    <tr>
                        <td align="center" style="background: linear-gradient(135deg, #059669 0%, #047857 100%); padding: 32px 24px; border-bottom: 3px solid #10b981;">
                            <img src="https://velearn.in/assets/images/velearn-logo.png" alt="VeLearn Logo" style="max-height: 48px; margin-bottom: 12px; display: block; filter: brightness(0) invert(1);">
                            <h1 style="color: #ffffff; margin: 0; font-size: 20px; font-weight: 700; letter-spacing: 0.5px;">Interview Call Letter & Details</h1>
                            <p style="color: #d1fae5; margin: 6px 0 0 0; font-size: 13px;">Congratulations on being shortlisted!</p>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 32px 32px 24px 32px;">
                            <p style="color: #334155; margin: 0 0 16px 0; font-size: 15px; font-weight: 600;">Dear {{ $interview->name }},</p>
                            
                            <p style="color: #475569; margin: 0 0 20px 0; font-size: 14px; line-height: 1.6;">
                                We are pleased to inform you that your interview has been successfully scheduled for the position of <strong>{{ $interview->role ?? 'Candidate' }}</strong> with <strong>{{ $interview->company }}</strong>.
                            </p>

                            <!-- Interview Schedule Card -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; margin-bottom: 24px; border-collapse: separate; overflow: hidden;">
                                <tr>
                                    <td colspan="2" style="background-color: #f8fafc; padding: 12px 18px; border-bottom: 1px solid #e2e8f0;">
                                        <strong style="color: #1e293b; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px;">Interview Schedule Details</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 18px; color: #64748b; font-size: 13px; width: 35%; border-bottom: 1px solid #f1f5f9;">Company</td>
                                    <td style="padding: 12px 18px; color: #0f172a; font-size: 13px; font-weight: 600; border-bottom: 1px solid #f1f5f9;">{{ $interview->company }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 18px; color: #64748b; font-size: 13px; border-bottom: 1px solid #f1f5f9;">Job Role</td>
                                    <td style="padding: 12px 18px; color: #0f172a; font-size: 13px; font-weight: 600; border-bottom: 1px solid #f1f5f9;">{{ $interview->role ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 18px; color: #64748b; font-size: 13px; border-bottom: 1px solid #f1f5f9;">Interview Date</td>
                                    <td style="padding: 12px 18px; color: #0f172a; font-size: 13px; font-weight: 600; border-bottom: 1px solid #f1f5f9;">
                                        {{ \Carbon\Carbon::parse($interview->date)->format('l, d F Y') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 18px; color: #64748b; font-size: 13px; border-bottom: 1px solid #f1f5f9;">Interview Time</td>
                                    <td style="padding: 12px 18px; color: #0f172a; font-size: 13px; font-weight: 600; border-bottom: 1px solid #f1f5f9;">
                                        {{ \Carbon\Carbon::parse($interview->time)->format('h:i A') }} (IST)
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 18px; color: #64748b; font-size: 13px; border-bottom: 1px solid #f1f5f9;">Interview Mode</td>
                                    <td style="padding: 12px 18px; color: #0f172a; font-size: 13px; font-weight: 600; border-bottom: 1px solid #f1f5f9;">
                                        <span style="display: inline-block; background-color: #ecfdf5; color: #047857; padding: 2px 8px; border-radius: 4px; font-size: 12px;">{{ $interview->interview_mode }}</span>
                                    </td>
                                </tr>
                                @if($interview->location)
                                <tr>
                                    <td style="padding: 12px 18px; color: #64748b; font-size: 13px; border-bottom: 1px solid #f1f5f9;">Location / Venue</td>
                                    <td style="padding: 12px 18px; color: #0f172a; font-size: 13px;">{{ $interview->location }}</td>
                                </tr>
                                @endif
                                @if($interview->meeting_link)
                                <tr>
                                    <td style="padding: 12px 18px; color: #64748b; font-size: 13px;">Meeting Link</td>
                                    <td style="padding: 12px 18px; color: #0f172a; font-size: 13px;">
                                        <a href="{{ $interview->meeting_link }}" target="_blank" style="color: #0284c7; font-weight: 600; text-decoration: underline;">Join Interview Room</a>
                                    </td>
                                </tr>
                                @endif
                            </table>

                            @if($interview->meeting_link)
                                <div style="text-align: center; margin-bottom: 24px;">
                                    <a href="{{ $interview->meeting_link }}" target="_blank" style="display: inline-block; background-color: #059669; color: #ffffff; text-decoration: none; padding: 12px 28px; font-size: 14px; font-weight: 700; border-radius: 6px; box-shadow: 0 2px 6px rgba(5,150,105,0.3);">
                                        🎥 Join Interview Meeting
                                    </a>
                                </div>
                            @endif

                            <!-- Instructions -->
                            <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 16px 20px; margin-bottom: 24px;">
                                <strong style="color: #1e293b; font-size: 13px; display: block; margin-bottom: 8px;">📌 Important Guidelines:</strong>
                                <ul style="margin: 0; padding-left: 20px; color: #475569; font-size: 13px; line-height: 1.6;">
                                    <li>Please be ready at least <strong>10 minutes prior</strong> to the scheduled time.</li>
                                    <li>Keep your updated resume and portfolio ready to share or present.</li>
                                    <li>Ensure a quiet environment with a working webcam, microphone, and stable internet connection.</li>
                                    <li>Dress professionally in formal or business casual attire.</li>
                                </ul>
                            </div>
                            
                            <p style="color: #334155; margin: 0; font-size: 14px;">
                                All the best for your interview!<br><br>
                                Warm regards,<br>
                                <strong style="color: #0f172a;">Placement Cell</strong><br>
                                <span style="color: #64748b; font-size: 12px;">VeLearn IT Solutions &middot; digital@velearn.in</span>
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td align="center" style="background-color: #f8fafc; padding: 20px; border-top: 1px solid #e2e8f0;">
                            <p style="color: #94a3b8; margin: 0 0 6px 0; font-size: 12px;">© {{ date('Y') }} VeLearn IT Solutions. All rights reserved.</p>
                            <a href="https://velearn.in" style="color: #059669; font-size: 12px; text-decoration: none; font-weight: 600;">Visit VeLearn Portal</a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
