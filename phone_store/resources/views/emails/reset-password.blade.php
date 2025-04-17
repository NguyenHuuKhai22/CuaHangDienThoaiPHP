<!DOCTYPE html>
<html>
<head>
    <title>Đặt Lại Mật Khẩu</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #000;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Xin chào!</h2>
        
        <p>Bạn nhận được email này vì chúng tôi đã nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn.</p>
        
        <a href="{{ route('password.reset', ['token' => $token, 'email' => $email]) }}" class="button">
            Đặt Lại Mật Khẩu
        </a>
        
        <p>Link đặt lại mật khẩu này sẽ hết hạn sau 60 phút.</p>
        
        <p>Nếu bạn không yêu cầu đặt lại mật khẩu, bạn không cần thực hiện thêm hành động nào.</p>
        
        <div class="footer">
            <p>Nếu bạn gặp vấn đề khi nhấp vào nút "Đặt Lại Mật Khẩu", hãy sao chép và dán URL sau vào trình duyệt web của bạn: {{ route('password.reset', ['token' => $token, 'email' => $email]) }}</p>
            
            <p>© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>