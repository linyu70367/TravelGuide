<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>登入頁面</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
            font-family: "Microsoft JhengHei", sans-serif;
        }

        body {
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #f2f4f8;
        }

        .login-box {
            width: 360px;
            padding: 30px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .login-box h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }

        .input-group-text {
            background: #f8fafc;
            color: #64748b;
        }

        .captcha-img {
            height: 38px;
            width: 100px;
            object-fit: cover;
            object-fit: contain;
            border-radius: 6px;
            cursor: pointer;
            border: 1px solid #dee2e6;
        }

        .login-btn {
            width: 100%;
            padding: 12px;
            margin-top: 10px;
            font-size: 16px;
        }
    </style>
</head>

<body>

    <div class="login-box">
        <h2><i class="bi bi-person-circle"></i> 管理員登入</h2>

        @if($errors->has("none"))
        <div class="text-danger text-center">{{ $errors->first('none') }}</div>
        @endif

        <form action="admin/login" method="post">
            @csrf
            <div class="mb-3">
                <label class="form-label">帳號</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                    <input type="text" class="form-control" placeholder="請輸入帳號" name="userName">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">密碼</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" class="form-control" placeholder="請輸入密碼" name="pwd">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">認證碼</label>

                @if($errors->has("code"))
                <span class="text-danger text-center">{{ $errors->first('code') }}</span>
                @endif

                <div class="d-flex gap-2">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-shield-check"></i></span>
                        <input type="text" class="form-control" name="code" placeholder="請輸入認證碼">
                    </div>

                    <img src="/captcha/flat" class="captcha-img" onclick="this.src='/captcha/flat?'+Math.random()" style="cursor: pointer">
                </div>
            </div>

            <button type="submit" class="btn btn-primary login-btn">
                <i class="bi bi-box-arrow-in-right"></i> 登入
            </button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>