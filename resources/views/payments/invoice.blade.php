<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice - {{ $invoiceNumber }}</title>
    <style>
        @page {
            margin: 28px 32px;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1e293b;
            font-size: 12px;
            line-height: 1.45;
            margin: 0;
            padding: 0;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
            padding-bottom: 18px;
            border-bottom: 2px solid #2563eb;
        }

        .logo {
            max-height: 48px;
            max-width: 170px;
        }

        .company-name {
            font-size: 20px;
            font-weight: bold;
            color: #0f172a;
            margin: 0 0 2px 0;
            letter-spacing: -0.3px;
        }

        .company-sub {
            font-size: 10px;
            color: #64748b;
            margin: 0;
        }

        .invoice-title {
            font-size: 20px;
            font-weight: 800;
            color: #1e40af;
            text-align: right;
            margin: 0 0 4px 0;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .invoice-meta {
            text-align: right;
            font-size: 11px;
            color: #475569;
        }

        .badge-paid {
            display: inline-block;
            background-color: #10b981;
            color: #ffffff;
            font-weight: bold;
            font-size: 11px;
            padding: 3px 12px;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 4px;
        }

        /* Two Column Info Section */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 22px;
        }

        .info-box {
            width: 48%;
            vertical-align: top;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 12px 14px;
        }

        .info-heading {
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: #2563eb;
            margin-bottom: 6px;
            border-bottom: 1px dashed #cbd5e1;
            padding-bottom: 4px;
        }

        .info-row {
            margin-bottom: 3px;
            font-size: 11px;
        }

        .info-label {
            color: #64748b;
            font-weight: 600;
            width: 90px;
            display: inline-block;
        }

        .info-val {
            color: #0f172a;
            font-weight: bold;
        }

        /* Main Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .items-table th {
            background-color: #1e3a8a;
            color: #ffffff;
            text-transform: uppercase;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.5px;
            padding: 8px 10px;
            text-align: left;
        }

        .items-table td {
            padding: 10px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 11px;
            vertical-align: middle;
        }

        .items-table tr.even {
            background-color: #f8fafc;
        }

        .course-title {
            font-weight: bold;
            color: #0f172a;
            font-size: 12px;
            margin-bottom: 2px;
        }

        .course-desc {
            font-size: 10px;
            color: #64748b;
        }

        /* Summary / Totals */
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .words-cell {
            width: 58%;
            vertical-align: top;
            padding: 10px 14px;
            background-color: #f1f5f9;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
        }

        .totals-cell {
            width: 38%;
            vertical-align: top;
        }

        .totals-table {
            width: 100%;
            border-collapse: collapse;
        }

        .totals-table td {
            padding: 5px 8px;
            font-size: 11px;
        }

        .totals-table tr.grand-total {
            background-color: #eff6ff;
            border-top: 2px solid #2563eb;
            border-bottom: 2px solid #2563eb;
        }

        .totals-table tr.grand-total td {
            font-size: 14px;
            font-weight: 800;
            color: #1e3a8a;
            padding: 8px;
        }

        /* Footer & Signatures */
        .footer-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 24px;
            padding-top: 14px;
            border-top: 1px solid #e2e8f0;
        }

        .terms-box {
            width: 60%;
            font-size: 9.5px;
            color: #64748b;
            line-height: 1.5;
            vertical-align: bottom;
        }

        .signature-box {
            width: 40%;
            text-align: right;
            vertical-align: bottom;
        }

        .signature-line {
            width: 160px;
            border-top: 1px solid #0f172a;
            margin-left: auto;
            margin-bottom: 4px;
        }

        .stamp-box {
            display: inline-block;
            border: 2px dashed #2563eb;
            color: #2563eb;
            padding: 4px 10px;
            font-size: 10px;
            font-weight: bold;
            border-radius: 4px;
            text-transform: uppercase;
            margin-bottom: 6px;
        }
    </style>
</head>
<body>

    @php
        $logoPath = public_path('assets/images/velearn-logo.png');
        $logoBase64 = file_exists($logoPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath)) : null;

        $student = $payment->admission->lead ?? $payment->admission->contact ?? null;
        $studentName = $student->name ?? 'Student';
        $studentPhone = $student->phone ?? $student->caller_number ?? 'N/A';
        $studentEmail = $student->email ?? 'N/A';
        $studentLocation = $student->location ?? 'Tamil Nadu, India';

        $courseTitle = $payment->admission->course->title ?? 'Professional Certification Course';
        $courseFees = $payment->admission->course_fees ?? $payment->amount;
        $batchType = $payment->admission->batch_type ?? 'Live Online Training';

        // Calculate all payments on this admission
        $totalPaid = 0;
        if($payment->admission) {
            $totalPaid = $payment->admission->payments()
                ->where(function($q) {
                    $q->where('status', 'Paid')->orWhere('status', 'paid');
                })->sum('amount');
        } else {
            $totalPaid = $payment->amount;
        }
        $dueAmount = max(0, $courseFees - $totalPaid);
    @endphp

    <!-- Header Table -->
    <table class="header-table">
        <tr>
            <td style="width: 55%; vertical-align: middle;">
                @if($logoBase64)
                    <img src="{{ $logoBase64 }}" class="logo" alt="VeLearn Logo">
                @else
                    <div class="company-name">VeLearn</div>
                @endif
                <div class="company-sub" style="margin-top: 4px;">
                    <strong>VeLearn Technologies Pvt Ltd</strong><br>
                    Website: www.velearn.in &nbsp;|&nbsp; Email: support@velearn.in<br>
                    GSTIN / Reg No: 33AAACV1234F1Z5
                </div>
            </td>
            <td style="width: 45%; vertical-align: middle; text-align: right;">
                <div class="invoice-title">Payment Receipt</div>
                <div class="invoice-meta">
                    <strong>Receipt No:</strong> {{ $receiptNumber }}<br>
                    <strong>Invoice No:</strong> {{ $invoiceNumber }}<br>
                    <strong>Date:</strong> {{ \Carbon\Carbon::parse($payment->payment_date)->format('d M, Y') }}<br>
                    <span class="badge-paid">&#10003; PAID</span>
                </div>
            </td>
        </tr>
    </table>

    <!-- Info Section (Bill To / Payment Details) -->
    <table class="info-table">
        <tr>
            <!-- Bill To Box -->
            <td class="info-box">
                <div class="info-heading">Billed To (Student Information)</div>
                <div class="info-row"><span class="info-label">Name:</span> <span class="info-val">{{ $studentName }}</span></div>
                <div class="info-row"><span class="info-label">Phone:</span> <span class="info-val">{{ $studentPhone }}</span></div>
                <div class="info-row"><span class="info-label">Email:</span> <span class="info-val">{{ $studentEmail }}</span></div>
                <div class="info-row"><span class="info-label">Location:</span> <span class="info-val">{{ $studentLocation }}</span></div>
            </td>
            <td style="width: 4%;"></td>
            <!-- Payment Transaction Box -->
            <td class="info-box">
                <div class="info-heading">Transaction & Payment Details</div>
                <div class="info-row"><span class="info-label">Payment Mode:</span> <span class="info-val">{{ strtoupper($payment->payment_method ?? 'Online') }}</span></div>
                <div class="info-row"><span class="info-label">Payment Status:</span> <span class="info-val" style="color: #059669;">{{ strtoupper($payment->status) }}</span></div>
                <div class="info-row"><span class="info-label">Transaction ID:</span> <span class="info-val" style="font-size: 10px;">{{ $payment->razorpay_payment_id ?? 'TXN-' . strtoupper(substr(md5($payment->id . $payment->created_at), 0, 12)) }}</span></div>
                @if($payment->razorpay_order_id)
                    <div class="info-row"><span class="info-label">Order Ref:</span> <span class="info-val" style="font-size: 10px;">{{ $payment->razorpay_order_id }}</span></div>
                @endif
            </td>
        </tr>
    </table>

    <!-- Course & Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 8%; text-align: center;">#</th>
                <th style="width: 47%;">Description / Course Details</th>
                <th style="width: 25%; text-align: center;">Training Mode</th>
                <th style="width: 20%; text-align: right;">Amount Paid (INR)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: center; font-weight: bold;">1</td>
                <td>
                    <div class="course-title">{{ $courseTitle }}</div>
                    <div class="course-desc">Comprehensive Live Classes, Industry Projects & Placement Assistance</div>
                    <div class="course-desc" style="color: #64748b; margin-top: 2px;">Total Course Fee: ₹{{ number_format($courseFees, 2) }}</div>
                </td>
                <td style="text-align: center;">
                    <span style="background: #f1f5f9; padding: 3px 8px; border-radius: 4px; font-weight: 600; font-size: 10px;">{{ $batchType }}</span>
                </td>
                <td style="text-align: right; font-weight: bold; font-size: 12px; color: #0f172a;">
                    ₹{{ number_format($payment->amount, 2) }}
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Summary / Totals Table -->
    <table class="summary-table">
        <tr>
            <td class="words-cell">
                <div style="font-size: 10px; font-weight: bold; text-transform: uppercase; color: #64748b; margin-bottom: 4px;">Total Amount Received in Words:</div>
                <div style="font-size: 11px; font-weight: bold; color: #1e3a8a; line-height: 1.4;">
                    @php
                        // Function to convert amount to words
                        function convertNumberToWords($number) {
                            $no = (int) floor($number);
                            $words = [
                                0 => '', 1 => 'One', 2 => 'Two', 3 => 'Three', 4 => 'Four', 5 => 'Five',
                                6 => 'Six', 7 => 'Seven', 8 => 'Eight', 9 => 'Nine', 10 => 'Ten',
                                11 => 'Eleven', 12 => 'Twelve', 13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
                                16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen', 19 => 'Nineteen', 20 => 'Twenty',
                                30 => 'Thirty', 40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty', 70 => 'Seventy',
                                80 => 'Eighty', 90 => 'Ninety'
                            ];
                            $digits = ['', 'Hundred', 'Thousand', 'Lakh', 'Crore'];
                            if ($no == 0) return 'Zero Rupees Only';
                            $str = [];
                            $i = 0;
                            while ($no > 0) {
                                $divider = ($i == 2) ? 10 : 100;
                                $number_chunk = $no % $divider;
                                $no = (int) ($no / $divider);
                                $i += ($divider == 10) ? 1 : 2;
                                if ($number_chunk) {
                                    $plural = (($counter = count($str)) && $number_chunk > 9) ? '' : '';
                                    $hundred = ($counter == 1 && $str[0]) ? ' and ' : '';
                                    if ($number_chunk < 21) {
                                        $str[] = $words[$number_chunk] . ' ' . $digits[$counter] . $plural . ' ' . $hundred;
                                    } else {
                                        $str[] = $words[floor($number_chunk / 10) * 10] . ' ' . $words[$number_chunk % 10] . ' ' . $digits[$counter] . $plural . ' ' . $hundred;
                                    }
                                }
                            }
                            $result = implode('', array_reverse($str));
                            return trim($result) . ' Rupees Only';
                        }
                    @endphp
                    {{ convertNumberToWords($payment->amount) }}
                </div>

                <div style="margin-top: 10px; font-size: 10px; color: #475569;">
                    <strong>Course Fee Summary:</strong> Total Fee: ₹{{ number_format($courseFees, 2) }} &nbsp;|&nbsp; Cumulative Paid: ₹{{ number_format($totalPaid, 2) }}
                    @if($dueAmount > 0)
                        &nbsp;|&nbsp; <span style="color: #dc2626; font-weight: bold;">Pending Due: ₹{{ number_format($dueAmount, 2) }}</span>
                    @else
                        &nbsp;|&nbsp; <span style="color: #16a34a; font-weight: bold;">Fully Paid</span>
                    @endif
                </div>
            </td>
            <td style="width: 4%;"></td>
            <td class="totals-cell">
                <table class="totals-table">
                    <tr>
                        <td style="color: #64748b;">Subtotal</td>
                        <td style="text-align: right; font-weight: bold;">₹{{ number_format($payment->amount, 2) }}</td>
                    </tr>
                    <tr>
                        <td style="color: #64748b;">Taxes (GST 18%)</td>
                        <td style="text-align: right; font-weight: bold; color: #64748b;">Included</td>
                    </tr>
                    <tr class="grand-total">
                        <td>Amount Paid</td>
                        <td style="text-align: right;">₹{{ number_format($payment->amount, 2) }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Footer & Terms -->
    <table class="footer-table">
        <tr>
            <td class="terms-box">
                <strong style="color: #0f172a;">Terms & Conditions:</strong><br>
                1. This is an official digital payment receipt generated by VeLearn Technologies.<br>
                2. Fees paid are non-refundable and non-transferable under any circumstances.<br>
                3. Please preserve this receipt for batch admission, attendance, and certification verification.<br>
                4. For any queries regarding this receipt, please contact <strong>support@velearn.in</strong>.
            </td>
            <td class="signature-box">
                <div class="stamp-box">&#10003; VERIFIED &amp; PAID</div><br>
                <div class="signature-line"></div>
                <div style="font-weight: bold; font-size: 10.5px; color: #0f172a;">VeLearn Technologies Pvt Ltd</div>
                <div style="font-size: 9px; color: #64748b;">Authorized Signatory</div>
            </td>
        </tr>
    </table>

    <div style="text-align: center; margin-top: 18px; font-size: 9px; color: #94a3b8; border-top: 1px dashed #e2e8f0; padding-top: 6px;">
        This is a computer-generated tax invoice &amp; receipt and does not require a physical signature. &copy; {{ date('Y') }} VeLearn. All rights reserved.
    </div>

</body>
</html>
