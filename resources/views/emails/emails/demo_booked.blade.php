<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demo Session Confirmed</title>
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
                            <span style="background-color: #24b8ec; color: #ffffff; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; display: inline-block; margin-bottom: 10px;">Booking Confirmed</span>
                            <h1 style="color: #22346b; margin: 0; font-size: 24px; font-weight: 700; letter-spacing: 0.5px;">Your Demo is Set!</h1>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 40px 30px 40px;">
                            <p style="color: #4a5568; margin: 0 0 20px 0; font-size: 16px; line-height: 1.6;">Hi {{ $bookingDetails['student_name'] ?? 'Student' }},</p>
                            <p style="color: #4a5568; margin: 0 0 30px 0; font-size: 16px; line-height: 1.6;">Great news! Your demo session has been successfully booked. We're excited to connect with you and walk you through what VeLearn has to offer.</p>

                            <!-- Data Box -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f8fafc; border-radius: 8px; border-left: 4px solid #24b8ec; margin-bottom: 35px;">
                                <tr>
                                    <td style="padding: 25px;">
                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                            <tr>
                                                <td style="padding-bottom: 15px;">
                                                    <p style="margin: 0 0 4px 0; color: #a0aec0; font-size: 12px; text-transform: uppercase; font-weight: 700; letter-spacing: 1px;">Assigned Staff</p>
                                                    <p style="margin: 0; color: #2d3748; font-size: 16px; font-weight: 600;">{{ $bookingDetails['staff_name'] }}</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-bottom: 0;">
                                                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                        <tr>
                                                            <td width="50%" valign="top">
                                                                <p style="margin: 0 0 4px 0; color: #a0aec0; font-size: 12px; text-transform: uppercase; font-weight: 700; letter-spacing: 1px;">Date</p>
                                                                <p style="margin: 0; color: #2d3748; font-size: 15px;">{{ $bookingDetails['date'] }}</p>
                                                            </td>
                                                            <td width="50%" valign="top">
                                                                <p style="margin: 0 0 4px 0; color: #a0aec0; font-size: 12px; text-transform: uppercase; font-weight: 700; letter-spacing: 1px;">Time</p>
                                                                <p style="margin: 0; color: #2d3748; font-size: 15px;">{{ $bookingDetails['time'] }}</p>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Zoom Link Box -->
                            @if(!empty($bookingDetails['zoom_link']))
                            <div style="text-align: center; margin-bottom: 30px;">
                                <p style="color: #4a5568; margin: 0 0 15px 0; font-size: 16px;">You can join the session at the scheduled time using the link below:</p>
                                <a href="{{ $bookingDetails['zoom_link'] }}" style="display: inline-block; padding: 14px 28px; background-color: #24b8ec; color: #ffffff; text-decoration: none; font-size: 16px; font-weight: 600; border-radius: 6px; box-shadow: 0 4px 6px rgba(36, 184, 236, 0.2);">Join Zoom Meeting</a>
                                <p style="margin: 20px 0 0 0; color: #a0aec0; font-size: 12px; word-break: break-all;">Or copy and paste this link:<br><a href="{{ $bookingDetails['zoom_link'] }}" style="color: #24b8ec;">{{ $bookingDetails['zoom_link'] }}</a></p>
                            </div>
                            @else
                            <div style="text-align: center; margin-bottom: 30px; padding: 20px; border-radius: 8px; background-color: #fffaf0; border: 1px solid #feebc8;">
                                <p style="color: #dd6b20; margin: 0; font-size: 15px; font-weight: 600;">Meeting details are being generated and will be sent to you shortly.</p>
                            </div>
                            @endif

                            <p style="color: #4a5568; margin: 0 0 10px 0; font-size: 15px; line-height: 1.6;">If you have any questions or need to reschedule, please don't hesitate to contact us.</p>
                            <p style="color: #4a5568; margin: 0; font-size: 15px; font-weight: 600;">Best Regards,<br>The VeLearn Team</p>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #22346b; padding: 30px; text-align: center;">
                            <p style="color: #a0aec0; margin: 0 0 10px 0; font-size: 13px;">&copy; {{ date('Y') }} VeLearn. All rights reserved.</p>
                            <p style="color: #718096; margin: 0; font-size: 12px;">This is an automated message, please do not reply directly to this email.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
