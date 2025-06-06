<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export Voucher</title>
    <style>
        body {

            font-family: myanmar, sans-serif;
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

        .header-table  {
            margin-left: 2rem;
        }

        .header-table td {
            border: none;
            vertical-align: top;

        }
        .logo {
            max-width: 100px;
            height: auto;
        }

             .logo_sm {
            width: 50px;
            height: 50px;
        }


        .title {
            text-align: center;
            color: #0046ad;
        }
        .bold{
            font-weight: bold;

        }

        .payment_table{
            /* Remove all borders except bottom */
border: none;

/* Add thick bottom border with dark gray color */
border-bottom: 3px solid #888888;
        }



        .payment_table tr  td{
            /* Remove all borders except bottom */
border: none;


        }

        .bolder{
            font-weight: 900;
            font-family: mm_bold , sans-serif;;


        }

        .cus_info{
            font-weight: 900;
        }
            .cus_info *{
            font-weight: 900;
        }
        .title h1 {
            font-size: 24px;
            margin: 0;
            font-weight: bold;
        }
        .title p {
            /* font-size: 14px; */
            margin: 5px 0;
            text-align:center;
        }
        .invoice-details {
            text-align: right;
            font-size: 12px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .section-title {
            font-size: 16px;
            font-weight: 900;
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
        .service-table  {
            text-align: center;
        }

        .no_bg{
            background-color: #F0F0F0;


            font-weight: bold;
            text-align: center;

        }
        .totals td {
            text-align: right;
        }
        .right{
            text-align: left !important;

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
            /* color: #333; */
        }
        .founder{
                border: none;
            font-size: 14px;
            color: #333;
            text-align: right;
        }
        .thank-you {
            color: #0046ad;
            font-style: italic;
            text-align: right;
        }

        .thank-you_one{
            text-align: right;

        }
    </style>
</head>
<body>
    <div class="voucher">
      <!-- Header -->
<table class="header-table">
    <tr>
        <td style="width: 10%; vertical-align: middle; text-align: center;">
            <img src="{{ $logo_base64 }}" alt="Cherry Lann Logo" class="logo">
        </td>
        <td style="width: 60%; vertical-align: middle; text-align: center;">
            <div class="title">
                <p style="font-size: 17px; margin: 0; font-weight: 900; text-align: end " class="bolder">CHERRY LANN - DIGITAL MARKETING</p>

            </div>
        </td>
        <td style="width: 30%; vertical-align: middle;">
            <div style="display: flex; flex-direction: column; justify-content: center; align-items: end; text-align: end; height: 100%;">
                <p style="margin: 5px 0;">INVOICE NUMBER: {{ $id }}</p>
                <p style="margin: 5px 0;">DATE: {{ $generated_date }}</p>
            </div>
        </td>
    </tr>
</table>


        <!-- Customer Information -->
        <table class='bold'>
            <tr>
                <td colspan="3" class="section-title bold">Customer Information</td>
            </tr>
            <tr>
                <td style="width: 33%;" class='bold'>
                    <strong class='bold' >Customer Name:</strong><br><span class='bold'>{{ $customer_name }}</span>
                </td>
                <td style="width: 33%;" class='bold'>
                    <strong class='bold' >Page Name:</strong><br><span class='bold'>{{ $page_name }}</span>
                </td>
                <td style="width: 33%;" class='bold'>
                    <strong class='bold' >Phone Number:</strong><br><span class='bold'>{{ $phone }}</span>
                </td>
            </tr>
        </table>

        <!-- Service Table -->
        <table >
            <thead>
                <tr>
                    <th class=" no_bg ">No.</th>
                    <th class=" no_bg">Service Type</th>
                    <th class=" no_bg">Price</th>
                    <th class=" no_bg">Qty</th>
                    <th class=" no_bg">Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="service-table">1</td>
                    <td class="service-table">{{ $boost_type_id }}</td>
                    <td class="service-table">{{ number_format($mm_kyat, 2) }}</td>
                    <td class="service-table">
                          @if(Str::contains($boost_type_id, 'Boosting'))
                        $
            @endif
                        {{ number_format($amount, 2) }}</td>
                      <td style="text-align: right;">{{ number_format($mm_kyat * $amount, 2) }}</td>
                </tr>

               <tr>
    <td colspan="3"></td>
    <td style="text-align: right; font-weight: bold;">
SUBTOTAL:
    </td>
    <td style="text-align: right; font-weight: bold;"> <span class="service-table"> {{ number_format($mm_kyat * $amount, 2) }} </span> </td>
</tr>
<tr>
    <td colspan="3"></td>
     <td style="text-align: right; font-weight: bold;">
DISCOUNT:
    </td>
    <td style="text-align: right; font-weight: bold;"> <span class="service-table"> {{ number_format($discount, 2) }} </span> </td>
</tr>

<tr>
    <td colspan="3"></td>
     <td style="text-align: right; font-weight: bold;">
TOTAL:
    </td>
    <td style="text-align: right; font-weight: bold;"> <span class="service-table"> {{ number_format($total_amount, 2) }} </span> </td>
</tr>



            </tbody>
        </table>




        <!-- Payment Methods -->
        <table class="payment_table">
            <tr>
                <td  colspan="2" style="text-align: left;" class="section-title no_bg">Payment Methods</td>
            </tr>
            <tr>
                <td>
                    <p class="bold"><span class="label">Account Name:</span> Win Zaw Oo</p>

                    <p><span class="label">Kpay:</span> 09 422 483 276</p>
                    <p><span class="label">Wave Pay:</span> 09 422 483 276</p>
                    <p><span class="label">AYA Pay:</span> 09 422 483 276</p>
                    <p><span class="label">Yoma Bank Account:</span> 0076 1018 0004 740</p>
                    <p><span class="label">CB Bank Account:</span> 0107 6001 0006 1239</p>
                </td>
                <td style="width: 50%;"></td>


            </tr>

              <tr>
                <td style="width: 50%;"></td>

                <td style="width: 50%;" class='thank-you_one'>
                              <img  src="{{ $sign_base64 }}" alt="Cherry Lann Logo" class=" logo">

                </td>
            </tr>
              <tr>
                <td style="width: 50%;"></td>

                <td style="width: 50%;" class='thank-you_one'>
                    <p >Win Zaw Oo</p>
                    <p >Founder</p>
                </td>
            </tr>
        </table>





        <!-- Footer -->
        <table class="footer">

            <tr>
                <td style="width: 50%;"></td>
                <td style="width: 50%;" class="thank-you">Thank you for choosing us !!</td>
            </tr>
        </table>
    </div>
</body>
</html>
