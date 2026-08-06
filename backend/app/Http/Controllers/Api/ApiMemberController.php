<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Upload;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ApiMemberController extends Controller
{
    public function getFrontMember(Request $req)
    {
        $member = Auth::user();
        if (empty($member)) {
            return response()->json([
                "message" => "未登入"
            ], 401);
        }
        return response()->json($member);
    }

    public function update(Request $req)
    {
        // 驗證資料格式
        try {
            $validated = $req->validate([
                'edit_email' => ['required', 'regex:/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/'],
                'edit_tel' => ['regex:/^[0-9-]*[0-9][0-9-]*$/'],
                'edit_avatar' => 'image|max:20480'
            ]);
        } catch (ValidationException) {
            return response()->json(['error' => '資料格式錯誤!!!']);
        }

        //上傳的圖檔
        $photo = $req->file('edit_avatar');
        //檔名
        $filename = "";
        //如果上傳的圖檔不是空的(有上傳圖)
        if (!empty($photo)) {

            if (!file_exists("images")) {
                mkdir("images", 0777);
            }
            chmod("images", 0777);
            chmod("images/member", 0777);
            $filename =  Upload::uploadPhoto($photo, "images/member", false, "", "", true, 140, 96);
        }


        $member = Auth::user();
        // $member = Member::find($user->id);
        $member->memberName = $req->edit_name;
        $member->birthday = $req->edit_birthday;
        $member->email = $req->edit_email;
        $member->tel = $req->edit_tel;
        $member->address = $req->edit_address;
        if (!empty($photo)) {
            $member->avatar = $filename;
        }
        $member->update();

        return response()->json([
            'message' => '成功修改!',
            "member" => $member
        ]);
    }

    public function checkEmail(Request $req)
    {
        return response()->json([
            'exist' => Member::checkEmail($req->email, Auth::user()->id)
        ]);
    }

    public function updatePwd(Request $req)
    {
        $req->validate([
            'oldpwd' => 'required',
            'newpwd' => 'required|min:8|confirmed',
        ]);

        $member = Auth::user();
        // 驗證舊密碼
        if (!Hash::check($req->oldpwd, $member->pwd)) {
            return response()->json([
                'success' => false,
                'message' => '目前密碼錯誤'
            ], 400);
        }

        // 更新密碼
        $member->pwd = Hash::make($req->newpwd);
        $member->save();
        return response()->json([
            'success' => true,
            'message' => '密碼修改成功'
        ]);
    }
}
