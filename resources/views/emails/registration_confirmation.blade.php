<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Confirmed – VeLearn</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f0f4f8; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased;">

    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f0f4f8; padding: 40px 0;">
        <tr>
            <td align="center">
                <table border="0" cellpadding="0" cellspacing="0" width="600" style="background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 8px 32px rgba(34,52,107,0.10);">

                    {{-- ══ HEADER ══ --}}
                    <tr>
                        <td align="center" style="background: linear-gradient(135deg, #0f2554 0%, #1a56db 100%); padding: 40px 30px 30px 30px;">
                            <img src="https://velearn.in/assets/images/velearn-logo.png" alt="VeLearn Logo" style="max-height: 50px; margin-bottom: 18px; display: block; filter: brightness(0) invert(1);">
                            <span style="background-color: rgba(255,255,255,0.18); color: #ffffff; padding: 5px 16px; border-radius: 30px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; display: inline-block; margin-bottom: 14px; border: 1px solid rgba(255,255,255,0.3);">
                                🎉 Registration Confirmed
                            </span>
                            <h1 style="color: #ffffff; margin: 0; font-size: 26px; font-weight: 700; letter-spacing: 0.3px; line-height: 1.3;">
                                Welcome to VeLearn!
                            </h1>
                        </td>
                    </tr>

                    {{-- ══ BODY ══ --}}
                    <tr>
                        <td style="padding: 40px 40px 10px 40px;">

                            {{-- Greeting --}}
                            <p style="color: #374151; margin: 0 0 10px 0; font-size: 17px; line-height: 1.6; font-weight: 600;">
                                Hi {{ $details['student_name'] ?? 'Student' }},
                            </p>
                            <p style="color: #6b7280; margin: 0 0 28px 0; font-size: 15px; line-height: 1.7;">
                                Congratulations! Your admission registration at VeLearn has been successfully completed. We are thrilled to have you on board. Below are your course registration details.
                            </p>

                            {{-- Info Box --}}
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background: linear-gradient(135deg, #eff6ff, #dbeafe); border-radius: 12px; border-left: 4px solid #1a56db; margin-bottom: 28px;">
                                <tr>
                                    <td style="padding: 24px 28px;">
                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                            {{-- Course --}}
                                            <tr>
                                                <td style="padding-bottom: 16px;">
                                                    <p style="margin: 0 0 4px 0; color: #6b7280; font-size: 11px; text-transform: uppercase; font-weight: 700; letter-spacing: 1.2px;">📚 Course</p>
                                                    <p style="margin: 0; color: #1e3a8a; font-size: 16px; font-weight: 700;">{{ $details['course_name'] ?? 'Course' }}</p>
                                                </td>
                                            </tr>
                                            {{-- Details --}}
                                            <tr>
                                                <td>
                                                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                        <tr>
                                                            <td width="50%" valign="top">
                                                                <p style="margin: 0 0 4px 0; color: #6b7280; font-size: 11px; text-transform: uppercase; font-weight: 700; letter-spacing: 1.2px;">🏫 Batch</p>
                                                                <p style="margin: 0; color: #374151; font-size: 14px; font-weight: 700;">{{ $details['batch_type'] ?? 'Standard' }}</p>
                                                            </td>
                                                            <td width="50%" valign="top">
                                                                <p style="margin: 0 0 4px 0; color: #6b7280; font-size: 11px; text-transform: uppercase; font-weight: 700; letter-spacing: 1.2px;">💰 Course Fees</p>
                                                                <p style="margin: 0; color: #166534; font-size: 14px; font-weight: 800;">₹{{ $details['course_fees'] ?? '0' }}</p>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            {{-- Next Steps --}}
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f0fdf4; border-radius: 8px; border-left: 4px solid #22c55e; margin-bottom: 32px;">
                                <tr>
                                    <td style="padding: 14px 18px;">
                                        <p style="margin: 0; color: #166534; font-size: 13px; line-height: 1.6;">
                                            🎓 <strong>What's Next?</strong> Your VeLearn coordinator will be in touch shortly to finalize your fee schedule. If applicable, you will receive a secure payment link in a separate email.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <p style="color: #6b7280; margin: 0 0 6px 0; font-size: 14px; line-height: 1.6;">
                                If you have any questions or need further assistance, please feel free to contact us.
                            </p>
                            <p style="color: #374151; margin: 0 0 0 0; font-size: 15px; font-weight: 600;">
                                Best Regards,<br>The VeLearn Team
                            </p>
                        </td>
                    </tr>

                    {{-- ══ FOOTER ══ --}}
                    <tr>
                        <td style="background: linear-gradient(135deg, #0f2554 0%, #1e3a8a 100%); padding: 30px 40px; text-align: center; margin-top: 20px;">
                            <p style="color: rgba(255,255,255,0.5); margin: 0 0 8px 0; font-size: 13px;">© {{ date('Y') }} VeLearn. All rights reserved.</p>
                            <p style="color: rgba(255,255,255,0.35); margin: 0; font-size: 12px;">This is an automated message. Please do not reply directly to this email.</p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>
