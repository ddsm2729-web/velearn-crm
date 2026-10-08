<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment {{ (strtolower($status ?? 'paid') === 'paid' || strtolower($status ?? 'paid') === 'success') ? 'Successful' : 'Failed' }} - VeLearn</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            background-color: #f8fafc;
            font-family: 'Outfit', sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            overflow: hidden;
            position: relative;
        }

        .success-card {
            background: #ffffff;
            padding: 50px 40px;
            border-radius: 24px;
            box-shadow: 0 20px 40px -10px rgba(0,0,0,0.08), 0 0 0 1px rgba(0,0,0,0.02);
            text-align: center;
            max-width: 480px;
            width: 90%;
            z-index: 10;
            opacity: 0;
            transform: translateY(30px);
            animation: fadeInUp 0.8s ease-out forwards;
        }

        @keyframes fadeInUp {
            to { opacity: 1; transform: translateY(0); }
        }

        .icon-container {
            position: relative;
            width: 90px;
            height: 90px;
            margin: 0 auto 30px;
        }

        .icon-circle {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.4);
            position: relative;
            z-index: 2;
            transform: scale(0);
            animation: popIn 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275) 0.3s forwards;
        }

        .icon-circle::after {
            content: '';
            position: absolute;
            top: -10px;
            left: -10px;
            right: -10px;
            bottom: -10px;
            border-radius: 50%;
            border: 2px solid #10b981;
            opacity: 0;
            animation: pulseRing 2s cubic-bezier(0.215, 0.61, 0.355, 1) infinite 1s;
        }

        .error-circle {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            box-shadow: 0 10px 25px rgba(239, 68, 68, 0.4);
        }
        
        .error-circle::after {
            border-color: #ef4444;
        }

        @keyframes popIn {
            to { transform: scale(1); }
        }

        @keyframes pulseRing {
            0% { transform: scale(0.9); opacity: 1; }
            100% { transform: scale(1.5); opacity: 0; }
        }

        h2 {
            color: #0f172a;
            font-weight: 700;
            letter-spacing: -0.5px;
            margin-bottom: 15px;
            opacity: 0;
            animation: fadeText 0.5s ease-out 0.6s forwards;
        }

        p.text-muted {
            color: #64748b !important;
            font-size: 1.05rem;
            line-height: 1.6;
            opacity: 0;
            animation: fadeText 0.5s ease-out 0.7s forwards;
        }

        @keyframes fadeText {
            to { opacity: 1; }
        }

        .transaction-box {
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            padding: 20px;
            border-radius: 16px;
            margin-bottom: 30px;
            opacity: 0;
            animation: fadeText 0.5s ease-out 0.8s forwards;
        }

        .transaction-box small {
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .transaction-box p {
            color: #0f172a;
            font-family: monospace;
            font-size: 1.1rem;
            letter-spacing: 1px;
            margin-top: 5px;
        }

        .btn-custom {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            padding: 14px 35px;
            border-radius: 50px;
            border: none;
            font-weight: 600;
            font-size: 1.05rem;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 10px 20px -10px rgba(59, 130, 246, 0.5);
            opacity: 0;
            animation: fadeText 0.5s ease-out 0.9s forwards;
            width: 100%;
        }

        .btn-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 25px -10px rgba(59, 130, 246, 0.6);
            color: white;
        }
        
        .btn-custom:active {
            transform: translateY(1px);
        }
    </style>
</head>
<body>

    <div class="success-card">
        @if(strtolower($status ?? 'paid') === 'paid' || strtolower($status ?? 'paid') === 'success')
            <div class="icon-container">
                <div class="icon-circle">
                    <i class="fas fa-check"></i>
                </div>
            </div>
            <h2>Payment Successful!</h2>
            <p class="text-muted">
                Thank you for your payment. Your transaction has been completed successfully and your receipt has been emailed to you.
            </p>
            @if($paymentId)
                <div class="transaction-box">
                    <small>Transaction Reference</small>
                    <p class="mb-0 fw-bold">{{ $paymentId }}</p>
                </div>
            @endif
        @else
            <div class="icon-container">
                <div class="icon-circle error-circle">
                    <i class="fas fa-times"></i>
                </div>
            </div>
            <h2>Payment Failed</h2>
            <p class="text-muted">
                We did not receive confirmation of your payment. If the amount was deducted from your account, it will be refunded automatically.
            </p>
        @endif

        <a href="https://velearn.in" class="btn-custom">Return to Website</a>
    </div>

</body>
</html>
