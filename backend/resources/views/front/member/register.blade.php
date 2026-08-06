@extends("front.layout")
@section("title", "會員註冊")
@push("style")
<link rel="stylesheet" href="/css/front/index.css">
<style>
    .register-bg {
        min-height: 100vh;
        position: relative;

        background:
            linear-gradient(rgba(0, 0, 0, 0.35),
                rgba(0, 0, 0, 0.35)),
            url('/images/register.jpg');

        background-size: cover;
        background-position: center;

        display: flex;
        align-items: center;
    }

    .register-bg .container {
        display: flex;
        justify-content: center;
    }


    /* 讓表單有透明玻璃感 */
    .bg-white.bg-opacity-90 {
        background-color: rgba(255, 255, 255, 0.9) !important;
    }
</style>
@endpush
@section("content")
<div class="register-bg">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-lg border-0 rounded-4 bg-white bg-opacity-90">
                    <div class="card-header bg-success text-white py-3">
                        <h3 class="mb-0 text-center">會員註冊</h3>
                    </div>
                    <div class="card-body p-4">
                        <form action="#" method="POST" id="form_register">
                            <div class="row g-3">

                                <!-- 錯誤訊息 -->
                                <!-- <div class="alert alert-danger mt-1 mb-0 text-center col-md-12" id="errormsg"></div> -->

                                <!-- 會員名稱 -->
                                <div class="col-md-12">
                                    <label class="form-label">會員名稱</label>
                                    <input type="text" class="form-control" name="memberName" id="memberName" value="{{ old('memberName') }}" placeholder="請輸入會員名稱">
                                    <div class="valid-feedback">名稱符合規則</div>
                                    <div class="invalid-feedback">請輸入名稱</div>
                                </div>

                                <!-- 會員信箱 -->
                                <div class="col-md-12">
                                    <label class="form-label">會員信箱</label>
                                    <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="請輸入 Email" id="email">
                                    <div class="valid-feedback">信箱符合規定</div>
                                    <div class="invalid-feedback">請輸入正確的信箱格式</div>
                                </div>

                                <!-- 會員密碼 -->
                                <div class="col-md-12">
                                    <label class="form-label">會員密碼</label>
                                    <input type="password" class="form-control" id="pwd" name="pwd" placeholder="請輸入10-30字密碼，須包含大小寫英文字、符號及數字">
                                    <div class="valid-feedback">密碼符合規定</div>
                                    <div class="invalid-feedback">請輸入正確的密碼格式</div>
                                </div>

                                <!-- 確認密碼 -->
                                <div class="col-md-12">
                                    <label class="form-label">確認密碼</label>
                                    <input type="password" id="pwd_confirmed" class="form-control" name="pwd_confirmation" placeholder="再次輸入密碼">
                                    <div class="valid-feedback">密碼確認</div>
                                    <div class="invalid-feedback">密碼不一致</div>
                                </div>

                                <!-- 會員電話 -->
                                <div class="col-md-12">
                                    <label class="form-label">會員電話</label>
                                    <input type="text" class="form-control" id="tel" name="tel" value="{{ old('tel') }}" placeholder="請輸入手機號碼">
                                    <div class="valid-feedback">電話格式符合</div>
                                    <div class="invalid-feedback">請輸入正確的電話格式</div>
                                </div>

                                <!-- 驗證碼 -->
                                <div class="col-md-12">
                                    <div class="row g-2 align-items-start">
                                        <div class="col-7">
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="bi bi-shield-check"></i>
                                                </span>
                                                <input type="text" class="form-control" name="code" placeholder="請輸入認證碼">

                                            </div>
                                            @if($errors->has("code"))
                                            <div class="alert alert-danger text-start mt-2 mb-0">{{ $errors->first('code') }}</div>
                                            @endif
                                        </div>

                                        <div class="col-5 text-end">
                                            <img src="/captcha/flat" id="captcha" class="img-fluid border rounded" style="height:38px; cursor:pointer;" onclick="this.src='/captcha/flat?'+Math.random()">
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <hr class="my-4">
                            <div class="d-flex justify-content-center gap-3">
                                <button type="submit" id="r_btn" class="btn btn-success px-5"><i class="bi bi-person-plus-fill"></i>註冊</button>
                                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary px-5"><i class="bi bi-arrow-left"></i>返回</a>
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
        let flag_memberName = false;
        let flag_email = false;
        let flag_pwd = false;
        let flag_pwd_confirmed = false;
        let flag_tel = false;


        $("#memberName").on("input", function() {
            let memberName = $(this).val().trim();

            if (memberName === "") {
                $(this).removeClass("is-valid");
                $(this).addClass("is-invalid");
                flag_memberName = false;
            } else {
                $(this).removeClass("is-invalid");
                $(this).addClass("is-valid");
                flag_memberName = true;
            }
        });
        $("#email").on("input", function() {
            let email = $(this).val();
            let reg = /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/;

            if (!reg.test(email)) {
                $(this).removeClass("is-valid");
                $(this).addClass("is-invalid");
                flag_email = false;
            } else {
                $(this).removeClass("is-invalid");
                $(this).addClass("is-valid");
                flag_email = true;
            }
        });

        $("#pwd").on("input", function() {
            let pwd = $(this).val();
            let reg = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[!@#$%^&*()_+\-=\[\]{};":\\|,.<>\/?]).{10,30}$/;

            if (!reg.test(pwd)) {
                $(this).removeClass("is-valid");
                $(this).addClass("is-invalid");
                flag_pwd = false;
            } else {
                $(this).removeClass("is-invalid");
                $(this).addClass("is-valid");
                flag_pwd = true;
            }

        });

        $("#pwd_confirmed").on("input", function() {
            let pwd_confirmed = $(this).val();
            let pwd = $("#pwd").val();
            if (pwd_confirmed === "" || pwd_confirmed !== pwd) {
                $(this).removeClass("is-valid");
                $(this).addClass("is-invalid");
                flag_pwd_confirmed = false;
            } else {
                $(this).removeClass("is-invalid");
                $(this).addClass("is-valid");
                flag_pwd_confirmed = true;
            }
        });

        $("#tel").on("input", function() {
            let tel = $("#tel").val();
            let reg = /^[0-9-]*[0-9][0-9-]*$/;
            if (tel === "" || !reg.test(tel)) {
                $(this).removeClass("is-valid");
                $(this).addClass("is-invalid");
                flag_tel = false;
            } else {
                $(this).removeClass("is-invalid");
                $(this).addClass("is-valid");
                flag_tel = true;
            }
        });

        if ($("#memberName").val()) $("#memberName").trigger("input");
        if ($("#email").val()) $("#email").trigger("input");
        if ($("#tel").val()) $("#tel").trigger("input");

        $("#form_register").on("submit", async function(e) {
            e.preventDefault();
            // 表單送出前再確認一次格式正確
            $("input[name='memberName']").trigger("input");
            $("#email").trigger("input");
            $("#pwd").trigger("input");
            $("#pwd_confirmed").trigger("input");
            $("#tel").trigger("input");

            if (!(flag_email && flag_pwd && flag_pwd_confirmed && flag_tel && flag_memberName)) {
                alert("欄位錯誤，請修正");
                return false;
            }

            try {
                let csrf = await axios.get('/sanctum/csrf-cookie');

                console.log("csrf成功", csrf);

                const formData = new FormData(this);
                const response = await axios.post('/member/store', formData);
                console.log(response);

                if(response.data.success)
                {
                    Swal.fire({
                        title: response.data.message,
                        icon: "success",
                        confirmButtonText: "確定",
                    }).then((result) => {
                        if (result.isConfirmed) {
                        }
                    });
                }else{
                    $("#captcha").trigger('click');
                    Swal.fire({
                        title: response.data.errors,
                        icon: "error",
                        confirmButtonText: "確定",
                    }).then((result) => {
                        if (result.isConfirmed) {
                        }
                    });
                }
            }catch(error){
                console.error("登入失敗", error);

                // 5. 處理後端驗證錯誤或驗證碼錯誤 (HTTP 422)
                if (error.response && error.response.status === 422) {
                    const errors = error.response.data.errors;
                    console.log(error);
                } else {
                    console.log(error);
                }
            }
        });

    });
</script>
@endsection