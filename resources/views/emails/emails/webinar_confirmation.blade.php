<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Confirmed - {{ $webinar->title }}</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f7f6; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f4f7f6; padding: 40px 0;">
        <tr>
            <td align="center">
                <table border="0" cellpadding="0" cellspacing="0" width="600" style="background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 8px 24px rgba(34,52,107,0.08);">
                    <!-- Header -->
                    <tr>
                        <td align="center" style="background-color: #ffffff; padding: 40px 20px 20px 20px; border-bottom: 1px solid #e2e8f0;">
                            <img src="https://velearn.in/assets/images/velearn-logo.png" alt="VeLearn Logo" style="max-height: 55px; margin-bottom: 15px; display: block;">
                            <span style="background-color: #24b8ec; color: #ffffff; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; display: inline-block; margin-bottom: 10px;">Registration Confirmed</span>
                            <h1 style="color: #22346b; margin: 0; font-size: 24px; font-weight: 700; letter-spacing: 0.5px;">You're All Set!</h1>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 40px 30px 40px;">
                            <p style="color: #4a5568; margin: 0 0 20px 0; font-size: 16px; line-height: 1.6;">Hi {{ $registration->name ?? 'Student' }},</p>
                            <p style="color: #4a5568; margin: 0 0 30px 0; font-size: 16px; line-height: 1.6;">Great news! You are successfully registered for our upcoming webinar. We're excited to have you join us and explore new insights together.</p>

                            <!-- Data Box -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f8fafc; border-radius: 8px; border-left: 4px solid #24b8ec; margin-bottom: 35px;">
                                <tr>
                                    <td style="padding: 25px;">
                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                            <tr>
                                                <td style="padding-bottom: 15px;">
                                                    <p style="margin: 0 0 4px 0; color: #a0aec0; font-size: 12px; text-transform: uppercase; font-weight: 700; letter-spacing: 1px;">Topic</p>
                                                    <p style="margin: 0; color: #2d3748; font-size: 16px; font-weight: 600;">{{ $webinar->title }}</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-bottom: 15px;">
                                                    <p style="margin: 0 0 4px 0; color: #a0aec0; font-size: 12px; text-transform: uppercase; font-weight: 700; letter-spacing: 1px;">Host</p>
                                                    <p style="margin: 0; color: #2d3748; font-size: 15px;">{{ $webinar->professor }}</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-bottom: 15px;">
                                                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                        <tr>
                                                            <td width="50%" valign="top">
                                                                <p style="margin: 0 0 4px 0; color: #a0aec0; font-size: 12px; text-transform: uppercase; font-weight: 700; letter-spacing: 1px;">Date</p>
                                                                <p style="margin: 0; color: #2d3748; font-size: 15px;">{{ \Carbon\Carbon::parse($webinar->date)->format('l, d M Y') }}</p>
                                                            </td>
                                                            <td width="50%" valign="top">
                                                                <p style="margin: 0 0 4px 0; color: #a0aec0; font-size: 12px; text-transform: uppercase; font-weight: 700; letter-spacing: 1px;">Time</p>
                                                                <p style="margin: 0; color: #2d3748; font-size: 15px;">{{ \Carbon\Carbon::parse($webinar->from_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($webinar->to_time)->format('h:i A') }}</p>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- CTA -->
                            <div style="text-align: center; margin-bottom: 10px;">
                                @if($webinar->meeting_link)
                                    <p style="color: #718096; margin: 0 0 20px 0; font-size: 14px;">Save this link to join the session on time:</p>
                                    <a href="{{ $webinar->meeting_link }}" style="background-color: #24b8ec; color: #ffffff; padding: 15px 35px; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 16px; display: inline-block;">Join Webinar Now</a>
                                    
                                    <div style="margin-top: 25px; padding: 15px; background-color: #f8fafc; border-radius: 6px;">
                                        <p style="margin: 0; color: #a0aec0; font-size: 12px;">If the button doesn't work, click or copy this link:</p>
                                        <a href="{{ $webinar->meeting_link }}" style="color: #24b8ec; font-size: 12px; word-break: break-all;">{{ $webinar->meeting_link }}</a>
                                    </div>
                                @else
                                    <div style="background-color: #fffaf0; border-left: 4px solid #ed8936; padding: 15px; text-align: left; border-radius: 4px;">
                                        <p style="margin: 0; color: #c05621; font-size: 14px;"><strong>Note:</strong> The meeting link is not yet available. We'll send it to you via email shortly before the session starts.</p>
                                    </div>
                                @endif
                            </div>
                            
                            <p style="margin-top: 35px; text-align: center; color: #718096; font-size: 15px;">
                                We look forward to seeing you there!<br>
                                <strong style="color: #22346b;">Team {{ config('app.name') }}</strong>
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td align="center" style="background-color: #f8fafc; padding: 30px 20px; border-top: 1px solid #e2e8f0;">
                            <p style="color: #718096; margin: 0 0 10px 0; font-size: 13px;">© {{ date('Y') }} {{ config('app.name') }}. All Rights Reserved.</p>
                            <p style="color: #a0aec0; margin: 0 0 15px 0; font-size: 12px;">You received this email because you registered for a webinar on our platform.</p>
                            <a href="https://velearn.in" style="color: #24b8ec; font-size: 13px; text-decoration: none; font-weight: 600;">VISIT OUR WEBSITE</a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
