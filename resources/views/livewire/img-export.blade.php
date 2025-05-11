<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export Voucher</title>
    <style>
        body {
            font-family: 'myanmar', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f7fa;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .logo-container {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    margin-bottom: 20px;
    gap: 1rem;
}

.logo {
    max-width: 5rem;
    height: auto;
}

.thank-you-text {
    color: #0046ad;
    font-size: 22px;
    border-bottom: 2px solid #e6f0ff;
    padding-bottom: 10px;
    font-weight: 600;
    white-space: nowrap;
}

    </style>
</head>
<body>
    <div style="width: 100%; max-width: 800px; background: linear-gradient(135deg, #ffffff 0%, #f0f8ff 100%); border-radius: 15px; box-shadow: 0 10px 30px rgba(0, 0, 102, 0.1); overflow: hidden;">
        <!-- Voucher Content -->
        <div style="padding: 30px 40px; position: relative;">
            <table style="width: 100%; text-align: center; margin-bottom: 20px;">
                <tr>
                    <td style="width: 25%;">
                        <img src="{{ $logo_base64 }}" alt="Logo" style="max-width: 80px;">
                    </td>
                    <td style="width: 50%;">
                        <span style="
                            color: #0046ad;
                            font-size: 22px;
                            font-weight: 600;
                            border-bottom: 2px solid #e6f0ff;
                            padding-bottom: 10px;
                            display: inline-block;
                            white-space: nowrap;
                        ">Thank you for choosing Us!</span>
                    </td>
                    <td style="width: 25%;">
                        <img src="{{ $logo_base64 }}" alt="Logo" style="max-width: 80px;">
                    </td>
                </tr>
            </table>


            <h2></h2>
            <div style="display: flex; flex-wrap: wrap; gap: 20px;">
                <div style="flex: 1; min-width: 250px;">
                    <div style="margin-bottom: 20px;">
                        <p style="font-size: 14px; color: #666; margin: 0 0 5px;">Customer Name</p>
                        <p style="font-size: 18px; color: #333; font-weight: 500; margin: 0; padding: 10px; background-color: rgba(230, 240, 255, 0.5); border-radius: 8px;">{{ $customer_name }}</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <p style="font-size: 14px; color: #666; margin: 0 0 5px;">Page Name</p>
                        <p style="font-size: 18px; color: #333; font-weight: 500; margin: 0; padding: 10px; background-color: rgba(230, 240, 255, 0.5); border-radius: 8px;">{{ $page_name }}</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <p style="font-size: 14px; color: #666; margin: 0 0 5px;">Phone</p>
                        <p style="font-size: 18px; color: #333; font-weight: 500; margin: 0; padding: 10px; background-color: rgba(230, 240, 255, 0.5); border-radius: 8px;">{{ $phone }}</p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <p style="font-size: 14px; color: #666; margin: 0 0 5px;">Service Type</p>
                        <p style="font-size: 18px; color: #333; font-weight: 500; margin: 0; padding: 10px; background-color: rgba(230, 240, 255, 0.5); border-radius: 8px;">{{ $boost_type_id }}</p>
                    </div>
                </div>
                <div style="flex: 1; min-width: 250px;">
                    <div style="margin-bottom: 20px;">
                        <p style="font-size: 14px; color: #666; margin: 0 0 5px;">Start Date</p>
                        <p style="font-size: 18px; color: #333; font-weight: 500; margin: 0; padding: 10px; background-color: rgba(230, 240, 255, 0.5); border-radius: 8px;">{{ $start_date }}</p>
                    </div>
                </div>
            </div>
            @if(Str::contains($boost_type_id, 'Boosting'))
            <div style="margin-top: 20px; text-align: center;">
                <div style="display: inline-block; background: linear-gradient(135deg, #0046ad 0%, #0073e6 100%); padding: 3px 5px; border-radius: 40px; box-shadow: 0 3px 8px rgba(0, 70, 173, 0.3);">
                    <p style="font-size: 13px; color: white; margin: 0 0 3px; font-weight: 300;">Quantity</p>
                    <p style="font-size: 18px; color: white; margin: 0; font-weight: 900;">${{ number_format($amount, 2) }}</p>
                </div>
            </div>
            @endif
            <div style="margin-top: 20px; text-align: center;">
                <div style="display: inline-block; background: linear-gradient(135deg, #0046ad 0%, #0073e6 100%); padding: 3px 5px; border-radius: 40px; box-shadow: 0 3px 8px rgba(0, 70, 173, 0.3);">
                    <p style="font-size: 13px; color: white; margin: 0 0 3px; font-weight: 300;">Amount</p>
                    <p style="font-size: 18px; color: white; margin: 0; font-weight: 900;">{{ number_format($mm_kyat, 2) }}</p>
                </div>
            </div>
            <div style="margin-top: 20px; text-align: center;">
                <div style="display: inline-block; background: linear-gradient(135deg, #0046ad 0%, #0073e6 100%); padding: 3px 5px; border-radius: 40px; box-shadow: 0 3px 8px rgba(0, 70, 173, 0.3);">
                    <p style="font-size: 13px; color: white; margin: 0 0 3px; font-weight: 300;">Total Amount</p>
                    <p style="font-size: 18px; color: white; margin: 0; font-weight: 900;">{{ number_format($total_amount, 2) }}</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
