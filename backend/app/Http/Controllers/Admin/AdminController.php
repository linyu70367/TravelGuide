<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Manager;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function home()
    {
        return view("admin.home");
    }

    public function login()
    {
        return view("admin.login");
    }

    public function doLogin(Request $req)
    {
        // 取得登入資料
        $userName = $req->userName;
        $pwd = $req->pwd;
        $code = $req->code;

        // 驗證碼確認
        if (captcha_check($code) == false) {
            return back()->withInput()->withErrors(["code" => "認證碼錯誤"]);
            exit;
        }

        // 檢查帳號密碼
        $manager = (new Manager())->getManager($userName, $pwd);
        if (empty($manager)) {
            return back()->withInput()->withErrors(["none" => "帳號或密碼錯誤"]);
        } else {
            session()->put("userName", $userName);
            return redirect("/admin/home");
        }
    }

    public function delete() {}
}
