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
        .logo {
            max-width: 100px;
            height: auto;
            position: absolute;
            top: 30px;
            right: 40px;
        }
    </style>
</head>
<body>
    <div style="width: 100%; max-width: 800px; margin: 20px; background: linear-gradient(135deg, #ffffff 0%, #f0f8ff 100%); border-radius: 15px; box-shadow: 0 10px 30px rgba(0, 0, 102, 0.1); overflow: hidden;">
        <!-- Voucher Content -->
        <div style="padding: 30px 40px; position: relative;">
            <h2 style="color: #0046ad; font-size: 22px; margin-bottom: 25px; border-bottom: 2px solid #e6f0ff; padding-bottom: 10px;">
                Thank you for choosing CHERRY LANN-DIGITAL MARKETING

            </h2>
            <div style="display: flex;  justify-content: center; width:100%">
                <img src="{{ $logo_base64 }}" alt="Cherry Lann Logo" class="logo" style="">
            </div>

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

            @if($boost_type_id == 'Boosting')
            <div style="margin-top: 20px; text-align: center;">
                <div style="display: inline-block; background: linear-gradient(135deg, #0046ad 0%, #0073e6 100%); padding: 7px 7px; border-radius: 50px; box-shadow: 0 5px 10px rgba(0, 70, 173, 0.3);">
                    <p style="font-size: 20px; color: white; margin: 0 0 5px; font-weight: 300;">Quantity</p>
                    <p style="font-size: 30px; color: white; margin: 0; font-weight: 900;">${{ number_format($amount, 2) }}</p>
                </div>
            </div>
            @endif
            <div style="margin-top: 20px; text-align: center;">
                <div style="display: inline-block; background: linear-gradient(135deg, #0046ad 0%, #0073e6 100%); padding: 7px 7px; border-radius: 50px; box-shadow: 0 5px 10px rgba(0, 70, 173, 0.3);">
                    <p style="font-size: 20px; color: white; margin: 0 0 5px; font-weight: 300;">Amount</p>
                    <p style="font-size: 30px; color: white; margin: 0; font-weight: 900;">{{ number_format($mm_kyat, 2) }}</p>
                </div>
            </div>
            <div style="margin-top: 20px; text-align: center;">
                <div style="display: inline-block; background: linear-gradient(135deg, #0046ad 0%, #0073e6 100%); padding: 7px 7px; border-radius: 50px; box-shadow: 0 5px 10px rgba(0, 70, 173, 0.3);">
                    <p style="font-size: 20px; color: white; margin: 0 0 5px; font-weight: 300;">Total Amount</p>
                    <p style="font-size: 30px; color: white; margin: 0; font-weight: 900;">{{ number_format($total_amount, 2) }}</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
