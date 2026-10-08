<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password - VeLearn</title>
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
                            <h1 style="color: #22346b; margin: 0; font-size: 24px; font-weight: 700; letter-spacing: 0.5px;">Password Reset Request</h1>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 40px 30px 40px;">
                            <p style="color: #4a5568; margin: 0 0 25px 0; font-size: 16px; line-height: 1.6;">Hello,</p>
                            <p style="color: #4a5568; margin: 0 0 35px 0; font-size: 16px; line-height: 1.6;">We received a request to reset the password for your VeLearn account. Click the button below to choose a new, secure password:</p>

                            <!-- CTA -->
                            <div style="text-align: center; margin: 0 0 40px 0;">
                                <a href="{{ $resetUrl }}" style="background-color: #24b8ec; color: #ffffff; padding: 15px 35px; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 16px; display: inline-block;">Reset Password</a>
                            </div>

                            <p style="color: #718096; margin: 0 0 25px 0; font-size: 14px; line-height: 1.5;">If you didn't request a password reset, you can safely ignore this email. Your password won't change until you create a new one.</p>
                            
                            <p style="color: #4a5568; margin: 0 0 35px 0; font-size: 15px;">
                                Best regards,<br>
                                <strong style="color: #22346b;">Team VeLearn</strong>
                            </p>

                            <!-- Fallback Link -->
                            <div style="background-color: #f8fafc; border-top: 2px solid #e2e8f0; padding: 20px; text-align: center; border-radius: 0 0 8px 8px;">
                                <p style="color: #a0aec0; margin: 0 0 10px 0; font-size: 12px; text-transform: uppercase; font-weight: 600;">If the button doesn't work, copy and paste this URL:</p>
                                <p style="margin: 0; color: #4a5568; font-size: 13px; word-break: break-all;">{{ $resetUrl }}</p>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td align="center" style="background-color: #f8fafc; padding: 30px 20px; border-top: 1px solid #e2e8f0;">
                            <p style="color: #718096; margin: 0 0 10px 0; font-size: 13px;">© {{ date('Y') }} VeLearn. All Rights Reserved.</p>
                            <p style="color: #a0aec0; margin: 0 0 15px 0; font-size: 12px;">Providing the best learning experience for your growth.</p>
                            <a href="https://velearn.in" style="color: #24b8ec; font-size: 13px; text-decoration: none; font-weight: 600;">VISIT OUR WEBSITE</a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
