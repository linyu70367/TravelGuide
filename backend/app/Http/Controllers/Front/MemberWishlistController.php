<?php

namespace App\Http\Controllers\Front;

use App\Models\MemberWishlist;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class MemberWishlistController extends Controller
{

    // R - Read (清單頁)：取得所有文章
    public function index()
    {
        // 抓取 SQLite 中最新的文章
        $posts = MemberWishlist::latest()->get();

        // 如果是寫 API，可以直接回傳 JSON：
        return response()->json($posts);

        // 如果是傳統網頁，可以帶給 Blade 畫面：
        // return view('posts.index', compact('posts'));
    }


    //C - Create (新增)：處理資料寫入
    public function store(Request $req)
    { // 1. 驗證前端傳過來的欄位資料
        $validated = $req->validate([
            'memberId'    => 'required|string|max:50',
            'viewsId'    => 'required|string|max:50'
        ]);

        // 2. 針對未傳入的選填欄位給予預設值（例如按讚數預設 0）
        // $validated['like'] = $validated['like'] ?? 0;

        // 3. 寫入資料庫
        $view = MemberWishlist::create($validated);

        // 4. 回傳成功訊息與剛建立好的資料 (HTTP Status: 201 Created)
        return response()->json([
            'message' => '景點資料建立成功！',
            'data'    => $view
        ], 201);
    }

    //R - Read (單篇詳情)：取得指定 ID 的文章
    public function show($id)
    {
        // findOrFail: 找不到此 ID 時會自動丟出 404 錯誤
        $post = MemberWishlist::findOrFail($id);

        return response()->json($post);
    }

    //U - Update (更新)：更新指定 ID 的文章
    public function update(Request $req, $id)
    {
        // 1. 找到要修改的文章
        $post = MemberWishlist::findOrFail($id);

        // 2. 驗證輸入資料
        $validated = $req->validate([
            'memberId'    => 'required|string|max:50',
            'viewsId'    => 'required|string|max:50'
        ]);

        // 3. 執行更新
        $post->update($validated);

        return response()->json([
            'message' => '文章更新成功！',
            'data' => $post
        ]);
    }

    //D - Delete (刪除)：刪除指定 ID 的文章
    public function destroy($id)
    {
        // 1. 找到文章
        $post = MemberWishlist::findOrFail($id);

        // 2. 從 SQLite 刪除
        $post->delete();

        return response()->json([
            'message' => '文章刪除成功！'
        ]);
    }
}
