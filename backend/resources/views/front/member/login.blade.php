@extends("front.layout")
@section("title", "會員登入")
@push("style")
<link rel="stylesheet" href="/css/front/index.css">
<style>
    .login-bg {
        min-height: 100vh;
        position: relative;

        background:
            linear-gradient(rgba(0, 0, 0, 0.35),
                rgba(0, 0, 0, 0.35)),
            url('/images/login.jpg');

        background-size: cover;
        background-position: center;

        display: flex;
        align-items: center;
    }

    /* .login-bg .container {
        display: flex;
        justify-content: center;
    } */


    /* 讓表單有透明玻璃感 */
    .bg-white.bg-opacity-90 {
        background-color: rgba(255, 255, 255, 0.9) !important;
    }
</style>
@endpush
@section("content")
<div class="login-bg">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="card shadow-lg border-0 rounded-4 bg-white bg-opacity-90">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <h2 class="fw-bold">會員登入</h2>
                            <p class="text-muted">歡迎回來，開始您的旅程</p>
                        </div>
                        <form action="" method="post" id="form_login">
                            <div class="text-danger text-center" id="errormsg"></div>
                            <div class="mb-3 form-floating">
                                <input type="text" name="email" class="form-control form-control-lg" placeholder="">
                                <label class="form-label">帳號:</label>
                            </div>

                            <div class="mb-3 form-floating">
                                <input type="password" name="pwd" class="form-control form-control-lg" placeholder="請輸入密碼">
                                <label class="form-label">密碼:</label>
                            </div>

                            <div class="row g-2 align-items-center">
                                <div class="col-7">
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-shield-check"></i>
                                        </span>
                                        <input type="text" class="form-control" name="code" placeholder="請輸入認證碼">
                                    </div>
                                </div>

                                <div class="col-5 text-end">
                                    <img src="/captcha/flat" id="captcha" class="img-fluid border rounded" style="max-height:70px; cursor:pointer;" onclick="this.src='/captcha/flat?'+Math.random()">
                                </div>
                            </div>
                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-success btn-lg">登入</button>
                            </div>

                            <div class="d-flex justify-content-around mt-4">
                                <a href="#" class="text-decoration-none">忘記密碼？</a>
                                <a href="/member/register" class="text-decoration-none">還沒註冊?</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        axios.defaults.withCredentials = true;
        $('#form_login').on("submit", async function(e) {
            e.preventDefault();

            const email = $('input[name="email"]').val();
            const pwd = $('input[name="pwd"]').val();
            const code = $('input[name="code"]').val();

            try {
                let csrf = await axios.get('/sanctum/csrf-cookie');

                console.log("csrf成功", csrf);

                const response = await axios.post('/member/doLogin', {
                    email,
                    pwd,
                    code
                });
                console.log(response);

                //登入成功，導向首頁
                window.location.href = '/views';
            } catch (error) {
                $("#captcha").trigger('click');
                console.error("登入失敗", error);
                // 處理 HTTP 401
                $("#errormsg").text(error.response.data.errors);


                // 5. 處理後端驗證錯誤或驗證碼錯誤 (HTTP 422)
                if (error.response && error.response.status === 422) {
                    const errors = error.response.data.errors;
                    console.log(error);
                } else {
                    console.log(error);
                }
                // 失敗時重新整理驗證碼
                $('.img-fluid').trigger('click');
            }
        });
    });
</script>
@endsection