<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نتیجه تراکنش</title>
    <style>
        body {
            font-family: Tahoma, sans-serif;
            direction: rtl;
            text-align: center;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .btn-back {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>نتیجه تراکنش</h2>

        <p><strong>شماره سفارش:</strong> {{ $order_id }}</p>
        <p><strong>مقدار شارژ شده:</strong> {{ number_format($amount) }} ریال</p>
        <p><strong>وضعیت تراکنش:</strong> {{ $transaction['status'] == 'completed' ? 'تکمیل شده' : 'ناموفق' }}</p>
        <p><strong>پیام:</strong> {{ $message }}</p>

        <a href="borotokar pro://back-to-app" class="btn-back">بازگشت به اپلیکیشن</a>
    </div>

</body>
</html>

