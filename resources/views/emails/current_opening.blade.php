<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Job Opening - VeLearn</title>
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
                            <h1 style="color: #22346b; margin: 0; font-size: 24px; font-weight: 700; letter-spacing: 0.5px;">New Opportunity Awaits!</h1>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 40px 30px 40px;">
                            <p style="color: #4a5568; margin: 0 0 20px 0; font-size: 16px; line-height: 1.6;">Hello,</p>
                            <p style="color: #4a5568; margin: 0 0 30px 0; font-size: 16px; line-height: 1.6;">We have an exciting new opening that might be a perfect fit for your skills and career goals.</p>

                            <!-- Data Box -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f8fafc; border-radius: 8px; border-left: 4px solid #24b8ec; margin-bottom: 30px;">
                                <tr>
                                    <td style="padding: 25px;">
                                        <p style="margin: 0 0 5px 0; color: #a0aec0; font-size: 12px; text-transform: uppercase; font-weight: 700; letter-spacing: 1px;">Position</p>
                                        <h3 style="margin: 0 0 20px 0; color: #2d3748; font-size: 20px;">{{ $opening->job_role }}</h3>
                                        
                                        <p style="margin: 0 0 5px 0; color: #a0aec0; font-size: 12px; text-transform: uppercase; font-weight: 700; letter-spacing: 1px;">Company</p>
                                        <p style="margin: 0; color: #24b8ec; font-size: 16px; font-weight: 600;">{{ $opening->company->company_name ?? 'Our Company' }}</p>
                                    </td>
                                </tr>
                            </table>

                            <p style="color: #4a5568; margin: 0 0 30px 0; font-size: 16px; line-height: 1.6;">If you're interested in this role, please review the requirements on our platform and apply as soon as possible.</p>
                            
                            <p style="color: #4a5568; margin: 0; font-size: 15px;">
                                Best regards,<br>
                                <strong style="color: #22346b;">Team VeLearn</strong>
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td align="center" style="background-color: #f8fafc; padding: 30px 20px; border-top: 1px solid #e2e8f0;">
                            <p style="color: #718096; margin: 0 0 10px 0; font-size: 13px;">© {{ date('Y') }} VeLearn IT Solutions. All Rights Reserved.</p>
                            <a href="https://velearn.in" style="color: #24b8ec; font-size: 13px; text-decoration: none; font-weight: 600;">VISIT OUR WEBSITE</a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
