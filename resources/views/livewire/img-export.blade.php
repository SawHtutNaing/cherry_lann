<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export Voucher</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f7fa;
        }
        .voucher {
            width: 800px;
            background: white;
            border: 1px solid #ddd;
            margin: 20px auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td, th {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-size: 14px;
            color: #333;
        }
        .header-table td {
            border: none;
            vertical-align: top;
        }
        .logo {
            max-width: 100px;
            height: auto;
        }
        .title {
            text-align: center;
            color: #0046ad;
        }
        .title h1 {
            font-size: 24px;
            margin: 0;
            font-weight: bold;
        }
        .title p {
            font-size: 14px;
            margin: 5px 0;
        }
        .invoice-details {
            text-align: right;
            font-size: 12px;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #333;
            background-color: #f0f0f0;
            padding: 5px;
        }
        .service-table th {
            background-color: #0046ad;
            color: white;
            font-weight: bold;
            text-align: center;
        }
        .service-table td {
            text-align: center;
        }
        .totals td {
            text-align: right;
        }
        .totals .label {
            font-weight: bold;
        }
        .payment-methods .label {
            font-weight: bold;
        }
        .footer td {
            border: none;
            font-size: 14px;
            color: #333;
        }
        .thank-you {
            color: #0046ad;
            font-style: italic;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="voucher">
        <!-- Header -->
        <table class="header-table">
            <tr>
                <td style="width: 20%;">
                    <img src="{{ $logo_base64 }}" alt="Cherry Lann Logo" class="logo">
                </td>
                <td style="width: 60%;">
                    <div class="title">
                        <h1>Cherry Lann</h1>
                        <p>Digital Marketing</p>
                    </div>
                </td>
                <td style="width: 20%;" class="invoice-details">
                    <p>INVOICE NUMBER: {{ $id }}</p>
                    <p>DATE: {{ $generated_date }}</p>
                </td>
            </tr>
        </table>

        <!-- Customer Information -->
        <table>
            <tr>
                <td colspan="3" class="section-title">Customer Information</td>
            </tr>
            <tr>
                <td style="width: 33%;">
                    <strong>Customer Name:</strong><br>{{ $customer_name }}
                </td>
                <td style="width: 33%;">
                    <strong>Page Name:</strong><br>{{ $page_name }}
                </td>
                <td style="width: 33%;">
                    <strong>Phone Number:</strong><br>{{ $phone }}
                </td>
            </tr>
        </table>

        <!-- Service Table -->
        <table class="service-table">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>Service Type</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>{{ $boost_type_id }}</td>
                    <td>{{ number_format($mm_kyat, 2) }}</td>
                    <td>
                          @if(Str::contains($boost_type_id, 'Boosting'))
                        $
            @endif

                        {{ number_format($amount, 2) }}</td>
                    <td>{{ number_format($mm_kyat * $amount, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Totals -->
        <table class="totals">
            <tr>
                <td style="width: 80%;"></td>
                <td><span class="label">SUBTOTAL:</span> {{ number_format($mm_kyat * $amount, 2) }}</td>
            </tr>
            <tr>
                <td></td>
                <td><span class="label">DISCOUNT:</span> {{ number_format($discount, 2) }}</td>
            </tr>
            <tr>
                <td></td>
                <td><span class="label">ADVANCE:</span> 0.00</td>
            </tr>
            <tr>
                <td></td>
                <td><span class="label">TOTAL:</span> {{ number_format($total_amount, 2) }}</td>
            </tr>
        </table>

        <!-- Founder -->
        <table>
            <tr>
                <td>
                    <p>Win Zaw Oo</p>
                    <p>Founder</p>
                </td>
            </tr>
        </table>

        <!-- Payment Methods -->
        <table>
            <tr>
                <td class="section-title">Payment Methods</td>
            </tr>
            <tr>
                <td>
                    <p><span class="label">Kpay:</span> 09 422 483 276</p>
                    <p><span class="label">Wave Pay:</span> 09 422 483 276</p>
                    <p><span class="label">AYA Pay:</span> 09 422 483 276</p>
                    <p><span class="label">Yoma Bank Account:</span> 0076 1018 0004 740</p>
                    <p><span class="label">CB Bank Account:</span> 0107 6001 0006 1239</p>
                    <p><span class="label">Account Name:</span> Win Zaw Oo</p>
                </td>
            </tr>
        </table>

        <!-- Footer -->
        <table class="footer">
            <tr>
                <td style="width: 50%;"></td>
                <td style="width: 50%;" class="thank-you">Thank you for your business!</td>
            </tr>
        </table>
    </div>
</body>
</html>
