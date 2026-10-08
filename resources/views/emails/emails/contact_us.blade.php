<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact Inquiry - VeLearn</title>
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
                            <h1 style="color: #22346b; margin: 0; font-size: 24px; font-weight: 700; letter-spacing: 0.5px;">New Contact Inquiry</h1>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 40px 30px 40px;">
                            <h2 style="color: #2d3748; margin: 0 0 20px 0; font-size: 22px; font-weight: 600;">Hello Admin,</h2>
                            <p style="color: #4a5568; margin: 0 0 30px 0; font-size: 16px; line-height: 1.6;">A new inquiry has been submitted through the <strong>Contact Us</strong> form on your website. Below are the details:</p>

                            <!-- Data Box -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0; margin-bottom: 30px;">
                                <tr>
                                    <td style="padding: 25px;">
                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                            <tr>
                                                <td style="padding-bottom: 20px;">
                                                    <p style="margin: 0 0 5px 0; color: #a0aec0; font-size: 12px; text-transform: uppercase; font-weight: 700; letter-spacing: 1px;">Full Name</p>
                                                    <p style="margin: 0; color: #2d3748; font-size: 16px; font-weight: 600;">{{ $data['name'] }}</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-bottom: 20px;">
                                                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                        <tr>
                                                            <td width="50%" valign="top">
                                                                <p style="margin: 0 0 5px 0; color: #a0aec0; font-size: 12px; text-transform: uppercase; font-weight: 700; letter-spacing: 1px;">Email Address</p>
                                                                <p style="margin: 0; color: #2d3748; font-size: 15px;">{{ $data['email_id'] }}</p>
                                                            </td>
                                                            <td width="50%" valign="top">
                                                                <p style="margin: 0 0 5px 0; color: #a0aec0; font-size: 12px; text-transform: uppercase; font-weight: 700; letter-spacing: 1px;">Phone Number</p>
                                                                <p style="margin: 0; color: #2d3748; font-size: 15px;">{{ $data['country_code'] }} {{ $data['phone_number'] }}</p>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-bottom: 20px;">
                                                    <p style="margin: 0 0 8px 0; color: #a0aec0; font-size: 12px; text-transform: uppercase; font-weight: 700; letter-spacing: 1px;">Interested Course</p>
                                                    <span style="background-color: #24b8ec; color: #ffffff; padding: 6px 14px; border-radius: 20px; font-size: 14px; font-weight: 600; display: inline-block;">{{ $data['custom_fields']['course'] ?? 'General Inquiry' }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-top: 15px; border-top: 1px solid #e2e8f0;">
                                                    <p style="margin: 0 0 10px 0; color: #a0aec0; font-size: 12px; text-transform: uppercase; font-weight: 700; letter-spacing: 1px;">Message Content</p>
                                                    <div style="background-color: #ffffff; padding: 18px; border-radius: 6px; border: 1px solid #e2e8f0; color: #4a5568; font-size: 15px; line-height: 1.6; font-style: italic;">
                                                        "{{ $data['custom_fields']['message'] ?? 'No message provided.' }}"
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- CTA -->
                            <div style="text-align: center; margin-top: 10px;">
                                <a href="mailto:{{ $data['email_id'] }}" style="background-color: #24b8ec; color: #ffffff; padding: 14px 32px; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 16px; display: inline-block; transition: background-color 0.3s;">Reply to Inquiry</a>
                            </div>
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
                <p style="color: #a0aec0; margin-top: 25px; font-size: 12px;">This is an automated notification from the VeLearn system.</p>
            </td>
        </tr>
    </table>
</body>
</html>
