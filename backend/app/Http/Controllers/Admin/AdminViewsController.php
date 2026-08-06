<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Img;
use App\Models\Views;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Upload;
use App\Models\ViewsType;
use Illuminate\Support\Facades\Log;

class AdminViewsController extends Controller
{
    private $dir = "images/views";
    public function list()
    {
        $views = Views::with(['types', 'imgs'])->paginate(5);
        $types = ViewsType::all();
        return view("admin.views.list", compact('views', 'types'));
        // return response()->json($views);
    }

    public function add()
    {
        return view("admin.views.add");
    }

    public function store(Request $req)
    {
        // 開始交易
        DB::beginTransaction();

        try {
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

            if ($req->hasFile('imgs')) {
                $this->savePhoto($view->id, $req->imgs);
            }

            // 全部成功
            DB::commit();

            return response()->json([
                "message" => "新增成功"
            ]);
        } catch (\Exception $e) {

            // 任一失敗即取消交易
            DB::rollBack();

            return response()->json([
                "message" => "新增失敗",
                "error" => $e->getMessage()
            ], 500);
        }
    }

    private function savePhoto(int $productId, ?array $imgs): void
    {
        // 如果沒有上傳圖，不處理
        if (empty($imgs)) {
            return;
        }

        // 如果要上傳的資料不存在
        if (!file_exists($this->dir)) {
            /*
                mkdir:建立資料夾
                0777: 所有人都可讀，寫，執行
                true: 當images資料夾不存在時，會同時建立images資料夾，再建立views資料夾(images/views)
                如果沒有寫true(預設是false),則當images不存在時，無法建立views資料夾
            */
            mkdir($this->dir, 0777, true);
        }

        foreach ($imgs as $img) {
            // 上傳產品圖到images/views, 同時自動產生小圖(140 * 96)
            $fileName = Upload::uploadPhoto($img, $this->dir, false, "", "", true, 140, 96);
            $img = new Img();
            // 產品代碼
            $img->viewsId = $productId;
            $img->imgSrc = $fileName;
            $img->save();
        }
    }

    public function edit()
    {
        return view("admin.views.edit");
    }

    public function update(Request $req)
    {
        // 開始交易
        DB::beginTransaction();

        try {
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
            $view = Views::find($req->id);
            $view->update($validated);

            if ($req->hasFile('imgs')) {
                $this->savePhoto($view->id, $req->imgs);
            }

            // 全部成功
            DB::commit();

            return response()->json([
                "message" => "修改成功"
            ]);
        } catch (\Throwable $e) {

            // 任一失敗即取消交易
            DB::rollBack();

            Log::error('Update View Failed: ' . $e->getMessage());

            return response()->json([
                "message" => "修改失敗",
                // 正式環境建議隱藏具體錯誤細節，開發環境（config('app.debug')）才顯示
                "error"   => config('app.debug') ? $e->getMessage() : '伺服器內部錯誤'
            ], 500);
        }
    }

    public function delete(Request $req)
    {
        // 開始交易
        DB::beginTransaction();

        try {

            // 要刪除的id[], 傳過來的是陣列
            $ids = $req->ids;
            // sweet alert的訊息
            $msg = "";
            //如果有勾選要刪除的選項
            if (!empty($ids)) {
                $msg = "已刪除";
                foreach ($ids as $id) {
                    // 取得要刪除的該筆資料
                    $view = Views::find($id);
                    // 取得檔名
                    $imgs = Img::where('viewsID', $id)->get();
                    foreach ($imgs as $img) {
                        // 將檔案由資料夾中刪除(含小圖)
                        unlink("images/views/" . $img->imgSrc);
                        unlink("images/views/S/" . $img->imgSrc);
                        // 將資料由news資料表刪除
                        $img->delete();
                    }
                    $view->delete();
                }
            } else {
                // 未勾選任何資料
                $msg = "未選擇要刪除的資料";
            }

            //完成交易
            Db::commit();

            return response()->json([
                'message' => $msg
            ]);
        } catch (\Throwable $e) {
            //退回交易
            DB::rollBack();

            Log::error('Delete View Failed: ' . $e->getMessage());

            $msg = "刪除失敗";
            return response()->json([
                "message" => $msg,
                // 正式環境建議隱藏具體錯誤細節，開發環境（config('app.debug')）才顯示
                "error"   => config('app.debug') ? $e->getMessage() : '伺服器內部錯誤'
            ], 500);
        }
    }
}
