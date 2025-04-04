<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export Voucher</title>
    <style>
        body {
            font-family: 'Poppins', Arial, sans-serif;
            background-color: #f5f7fa;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .voucher-container {
            width: 100%;
            max-width: 800px;
            margin: 20px;
            background: linear-gradient(135deg, #ffffff 0%, #f0f8ff 100%);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 102, 0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #0046ad 0%, #0073e6 100%);
            padding: 25px;
            text-align: center;
            color: white;
        }

        .content {
            padding: 30px 40px;
        }

        .section-title {
            color: #0046ad;
            font-size: 22px;
            margin-bottom: 25px;
            border-bottom: 2px solid #e6f0ff;
            padding-bottom: 10px;
        }

        .voucher-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .voucher-table td {
            padding: 12px;
            vertical-align: top;
        }

        .field-label {
            font-size: 14px;
            color: #666;
            margin: 0 0 5px;
        }

        .field-value {
            font-size: 18px;
            color: #333;
            font-weight: 500;
            margin: 0;
            padding: 10px;
            background-color: rgba(230, 240, 255, 0.5);
            border-radius: 8px;
        }

        .amount-circle {
            display: inline-block;
            background: linear-gradient(135deg, #0046ad 0%, #0073e6 100%);
            padding: 15px 25px;
            border-radius: 50px;
            box-shadow: 0 5px 10px rgba(0, 70, 173, 0.3);
            text-align: center;
            margin: 10px;
        }

        .amount-label {
            font-size: 12px;
            color: white;
            margin: 0 0 5px;
            font-weight: 300;
        }

        .amount-value {
            font-size: 20px;
            color: white;
            margin: 0;
            font-weight: 700;
        }

        .footer {
            background-color: #f0f8ff;
            padding: 20px;
            text-align: center;
            border-top: 1px dashed #cce0ff;
        }

        .footer-text {
            margin: 0;
            color: #0046ad;
            font-size: 14px;
        }

        .footer-date {
            margin: 5px 0 0;
            color: #666;
            font-size: 12px;
        }

        @media (max-width: 600px) {
            .voucher-table td {
                display: block;
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="voucher-container">
        <!-- Header -->
        {{-- <div class="header">
            <img src="{{ public_path('images/logo.jpeg') }}" alt="Cherry Lann Digital Marketing Logo" style="max-width: 200px; height: auto; margin-bottom: 10px;">
            <h1 style="margin: 10px 0 0; font-size: 28px; font-weight: 600; letter-spacing: 1px; text-transform: uppercase;">Digital Marketing Voucher</h1>
        </div> --}}

        <!-- Voucher Content -->
        <div class="content">
            {{-- <img src="{{ asset('images/logo.jpeg') }}" alt="Cherry Lann Logo" class="h-12 mx-auto mb-4"> --}}

            <h2 class="section-title">Voucher Details</h2>

            <table class="voucher-table">
                <tr>
                    <td width="50%">
                        <p class="field-label">Page Name</p>
                        <p class="field-value">{{ $page_name }}</p>
                    </td>
                    <td width="50%">
                        <p class="field-label">Start Date</p>
                        <p class="field-value">{{ $start_date }}</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p class="field-label">Boost Type</p>
                        <p class="field-value">{{ $boost_type_id }}</p>
                    </td>
                    <td>
                        <p class="field-label">Status</p>
                        @php
                            $statusColor = $status == 1 ? '#00a846' : '#ff4c4c';
                            $statusBg = $status == 1 ? 'rgba(0, 200, 83, 0.1)' : 'rgba(255, 76, 76, 0.1)';
                            $statusText = $status == 1 ? 'Charge' : 'Refund';
                        @endphp
                        <p style="font-size: 18px; color: {{ $statusColor }}; font-weight: 500; margin: 0; padding: 10px; background-color: {{ $statusBg }}; border-radius: 8px;">
                            {{ $statusText }}
                        </p>
                    </td>
                </tr>
            </table>

            <table class="voucher-table" style="text-align: center;">
                <tr>
                    <td width="33%">
                        <div class="amount-circle">
                            <p class="amount-label">Amount</p>
                            <p class="amount-value">${{ number_format($amount, 2) }}</p>
                        </div>
                    </td>
                    <td width="33%">
                        <div class="amount-circle">
                            <p class="amount-label">MM Kyat</p>
                            <p class="amount-value">${{ number_format($mm_kyat, 2) }}</p>
                        </div>
                    </td>
                    <td width="33%">
                        <div class="amount-circle">
                            <p class="amount-label">Total Amount</p>
                            <p class="amount-value">${{ number_format($total_amount, 2) }}</p>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p class="footer-text">Thank you for choosing Cherry Lann Digital Marketing</p>
            <p class="footer-date">This voucher was generated on {{ $generated_date }}</p>
        </div>
    </div>
</body>
</html>
