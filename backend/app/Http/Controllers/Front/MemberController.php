<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;


class MemberController extends Controller
{
    // 會員中心頁面
    public function home()
    {
        // 回傳會員中心頁面
        return view("front.member.home");
    }

    // 會員登入頁面
    public function login()
    {
        // 回傳login頁面
        return view("front.member.login");
    }

    // 會員註冊頁面
    public function register()
    {
        return view("front.member.register");
    }

    // 會員登入
    public function doLogin(Request $req)
    {

        // 驗證碼確認
        if (captcha_check($req->code) == false) {
            return response()->json(["errors" => "認證碼錯誤"], 401);
        }

        // // 查詢會員
        // $member = Member::where('email', $req->email)->where('pwd', $req->pwd)->first();

        // if (empty($member)) {
        //     return back()->withInput()->withErrors(["none" => "帳號或密碼錯誤"]);
        // } else {
        //     session()->put("memberId", $member->id);
        //     return redirect("/member/home");
        // }

        //手動處理登入
        // $member = Member::where('email',$req->email)->first();

        // if (!$member || !Hash::check($req->pwd, $member->pwd)){
        //     return response()->json([
        //         'errormsg' => '帳號密碼錯誤'
        //     ], 401);
        // }

        // Auth::login($member);

        // Auth 處理登入
        if (!Auth::attempt([
            'email' => $req->email,
            'password' => $req->pwd
        ])) {
            return response()->json([
                'errors' => '帳號密碼錯誤'
            ], 401);
        }

        $req->session()->regenerate();

        return response()->json([
            'message' => '登入成功'
        ]);
    }

    // 會員登出
    public function logout(Request $req)
    {
        // 全部清除session(暫存)
        // session()->flush();
        // 清除個別session
        // Session::forget("memberId");

        //改用Auth
        Auth::logout();
        $req->session()->invalidate();
        $req->session()->regenerateToken();
        return response()->json([
            "message" => "logout success"
        ]);
    }

    // 會員註冊
    public function store(Request $req)
    {
        // 驗證資料格式
        try {
            $validated = $req->validate([
                'email' => ['required', 'regex:/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/'],
                'pwd' => ['required', 'regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[!@#$%^&*()_+\-=\[\]{};":\\|,.<>\/?]).{10,30}$/'],
                'tel' => ['regex:/^[0-9-]*[0-9][0-9-]*$/']
            ]);
        } catch (ValidationException) {
            return response()->json([
                'success' => false,
                'errors' => '資料格式錯誤!'
            ]);
        }

        // 驗證碼
        $code = $req->code;
        if (!captcha_check($code)) {
            return response()->json([
                'success' => false,
                'errors' => '驗證碼錯誤!'
            ]);
        }

        if (Member::checkEmail($req->email)) {
            return response()->json([
                'success' => false,
                "errors" => "信箱已存在，請用其他信箱!"
            ]);
        }

        $member = new Member();
        $member->memberName = $req->memberName;
        $member->email = $req->email;
        $member->pwd = $req->pwd;
        $member->tel = $req->tel;
        $member->address = "";
        $member->birthday = "";
        $member->avatar = "default_avatar.png";
        $member->status = "未驗證";
        $member->save();

        return  response()->json([
            'success' => true,
            'message' => '註冊成功!'
        ]);
    }
}
