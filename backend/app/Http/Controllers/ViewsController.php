<?php

namespace App\Http\Controllers;

use App\Models\Views;
use Illuminate\Http\Request;

class ViewsController extends Controller
{
    // R - Read (清單頁)：取得所有資料
    public function index()
    {
        // 抓取 SQLite 中最新的景點資料
        $views = Views::latest()->get();

        return response()->json([
            'status' => 'success',
            'data'   => $views
        ], 200);
    }

    public function like10()
    {
        // 依 like 欄位由高到低排序，取得前 10 筆景點資料
        $views = Views::orderByDesc('like')
            ->limit(10)
            ->get();

        return response()->json([
            'status' => 'success',
            'data'   => $views
        ], 200);
    }

    public function viewsandtype()
    {
        $views = Views::join('views_types', 'views.typeId', '=', 'views_types.id')
            ->select(
                'views.id',
                'views.name',
                'views_types.typeName as typeName'
            )
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $views,
        ], 200);
    }

    // C - Create (新增)：處理資料寫入
    public function store(Request $req)
    {
        // 1. 驗證資料（完全對齊 Migration 欄位規則）
        $validated = $req->validate([
            'name'    => 'required|string|max:100', // 唯一必填欄位
            'city'    => 'nullable|string|max:50',
            'town'    => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'typeId'  => 'nullable|integer',         // 整數，資料庫預設為 1
            'brief'   => 'nullable|string|max:255',
            'content' => 'nullable|string',          // 長文字內容
            'tel'     => 'nullable|string|max:30',
            'like'    => 'nullable|integer|min:0',   // 整數，資料庫預設為 0
        ]);

        // 2. 針對未傳入的選填欄位補上預設值
        $validated['typeId'] = $validated['typeId'] ?? 1;
        $validated['like']   = $validated['like']   ?? 0;

        // 3. 寫入資料庫
        $view = Views::create($validated);

        // 4. 回傳成功 response (HTTP 201 Created)
        return response()->json([
            'status'  => 'success',
            'message' => '景點資料建立成功！',
            'data'    => $view
        ], 201);
    }

    // R - Read (單篇詳情)：取得指定 ID 的資料
    public function show($id)
    {
        // findOrFail: 找不到此 ID 時自動拋出 404
        $view = Views::with(['imgs', 'types'])->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data'   => $view
        ], 200);
    }

    // U - Update (更新)：更新指定 ID 的資料
    public function update(Request $req, $id)
    {
        // 1. 找到要修改的景點
        $view = Views::findOrFail($id);

        // 2. 驗證輸入資料（更新時全部使用 nullable，前端沒帶過來的欄位就不更動）
        $validated = $req->validate([
            'name'    => 'sometimes|required|string|max:100', // 如果有傳 name 欄位則不能為空
            'city'    => 'nullable|string|max:50',
            'town'    => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'typeId'  => 'nullable|integer',
            'brief'   => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'tel'     => 'nullable|string|max:30',
            'like'    => 'nullable|integer|min:0',
        ]);

        // 3. 執行更新
        $view->update($validated);

        return response()->json([
            'status'  => 'success',
            'message' => '景點資料更新成功！',
            'data'    => $view
        ], 200);
    }

    // U - Update (局部更新)：僅更新前端有傳送的欄位
    public function patch(Request $req, $id)
    {
        // 1. 找到要修改的資料，找不到會直接回傳 404
        $view = Views::findOrFail($id);

        // 2. 驗證輸入資料（使用 sometimes，表示有傳入才驗證，沒傳入就跳過）
        $validated = $req->validate([
            'name'    => 'sometimes|required|string|max:50',
            'city'    => 'sometimes|required|string|max:20',
            'town'    => 'sometimes|required|string|max:20',
            'address' => 'sometimes|required|string|max:100',
            'typeId'  => 'sometimes|required|integer|min:0',
            'brief'   => 'sometimes|nullable|string|max:50',
            'content' => 'sometimes|nullable|string|max:255',
            'tel'     => 'sometimes|nullable|string|max:20',
            'like'    => 'sometimes|nullable|integer|min:0',
        ]);

        // 3. 執行更新（只會更新 $validated 陣列裡有的 key）
        $view->update($validated);

        // 4. 回傳成功訊息與更新後的完整資料
        return response()->json([
            'message' => '景點資料局部更新成功！',
            'data'    => $view
        ], 200);
    }

    //D - Delete (刪除)：刪除指定 ID 的文章
    public function destroy($id)
    {
        // 1. 找到景點
        $view = Views::findOrFail($id);

        // 2. 從 SQLite 刪除
        $view->delete();

        return response()->json([
            'status'  => 'success',
            'message' => '景點資料刪除成功！'
        ], 200);
    }

    // api查詢類別及關鍵字
    public function getView(Request $req)
    {
        $list = (new Views())->getView($req);
        return response()->json([
            "success" => true,
            "list" => $list
        ]);
    }

    public function getWishView()
    {
        $list = Views::with('wishlists')->get();
        return response()->json([
            'status' => true,
            'data' => $list
        ]);
    }
}
