<?php

namespace App\Http\Controllers\Admin;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;

class MemberController extends Controller
{

    // R - Read : 取得所有會員
    public function list()
    {
        // 取得所有會員
        $members = (new Member())->getAdminMembers();

        // 回傳json格式
        // return response()->json($members);

        // 如果要回傳view寫在下面
        return view("admin.member.list", compact("members"));
    }

    // 導向會員註冊頁面
    public function create()
    {
        // 如果要回傳view寫在下面
        return view("admin.member.add");
    }

    //C - Create (新增)：處理會員資料寫入
    public function store(Request $req)
    {
        // 新增會員
        $member = new Member();
        $member->name = $req->name;
        $member->email = $req->email;
        $member->pwd = $req->pwd;
        // 如果未提供電話，設為""
        $member->tel = ($req->tel ?? "");
        $member->save();

        // 回傳成功訊息與剛建立好的資料 (HTTP Status: 201 Created)
        return response()->json([
            'message' => '會員資料建立成功！',
            'member'    => $member
        ], 201);
    }

    public function edit(Request $req)
    {
        // 尋找會員id
        $member = Member::find($req->id);

        return view("admin.member.edit", compact("member"));
    }


    //U - Update (更新)：更新會員資料
    public function update(Request $req)
    {
        $memberModel = new Member();
        if ($memberModel->checkEmail($req)) {
            return back()->withInput()->withErrors(["emailexist" => "信箱已存在，請用其他信箱!"]);
        }

        $member = Member::find($req->id);
        $member->memberName = $req->memberName;
        $member->email = $req->email;
        $member->tel = $req->tel;
        $member->update();

        return redirect("/admin/member/list");
    }

    //D - Delete (刪除)：刪除指定 ID 的會員
    public function delete(Request $req)
    {
        $ids = $req->id;
        $msg = "";

        if (!empty($ids)) {
            $msg = "已刪除";
            foreach ($ids as $id) {
                $member = Member::find($id);
                $member->delete();
            }
        } else {
            $msg = "請選擇要刪除的資料";
        }

        Session::flash("message", $msg);
        return redirect("admin/member/list");
    }
    //R - Read (單篇詳情)：取得指定 ID 的文章
    public function show($id)
    {
        // findOrFail: 找不到此 ID 時會自動丟出 404 錯誤
        $post = Member::findOrFail($id);

        return response()->json($post);
    }

    public function getMembers()
    {
        $cnt = Member::all()->count();
        return response()->json([
            'status' => true,
            'cnt' => $cnt
        ]);
    }
}
