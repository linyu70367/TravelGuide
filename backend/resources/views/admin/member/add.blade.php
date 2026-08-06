<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>addtest</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <form action="/member/store" method="POST">
            @csrf
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="name">姓名 <span style="color:var(--danger)">*</span></label>
                    <div class="input-icon-wrap">
                        <i class="fa fa-user icon"></i>
                        <input type="text" id="name" name="name" class="form-control" placeholder="請輸入姓名" required />
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label" for="email">電子郵件 <span style="color:var(--danger)">*</span></label>
                <div class="input-icon-wrap">
                    <i class="fa fa-envelope icon"></i>
                    <input type="text" id="email" name="email" class="form-control" placeholder="example@email.com" required />
                </div>
                <label class="form-label" for="pwd">密碼 <span style="color:var(--danger)">*</span></label>
                <div class="input-icon-wrap">
                    <i class="fa fa-envelope icon"></i>
                    <input type="text" id="pwd" name="pwd" class="form-control" required />
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="tel">手機號碼</label>
                    <div class="input-icon-wrap">
                        <i class="fa fa-mobile-alt icon"></i>
                        <input type="text" id="tel" name="tel" class="form-control" placeholder="09xxxxxxxx" />
                    </div>
                </div>
            </div>

        </form>
    </div>
</body>
<script src="/public//js/bootstrap.bundle.min.js"></script>

</html>