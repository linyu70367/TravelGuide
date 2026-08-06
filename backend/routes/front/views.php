<?php

use App\Http\Controllers\Front\Views\FrontViewsController;
use App\Http\Controllers\ViewsController;
use App\Models\Img;
use App\Models\Views;
use App\Models\ViewsType;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/views-0', function () {
    // 直接走 Eloquent ORM，速度最快且不會有轉字串代價
    $views = Views::latest()->get();
    $viewstype = ViewsType::latest()->get();
    $img = Img::latest()->get();

    return view('front.views.views-0', compact('views','viewstype','img'));
});

Route::get('/views-1', function () {
    // 使用 with('imgs') 預載入關聯圖片
    $views = Views::with('imgs')->latest()->get();
    $viewstype = ViewsType::latest()->get();

    return view('front.views.views-1', compact('views', 'viewstype'));
});


Route::get('/views', function (Request $request) {
    $views = Views::with('imgs')->latest()->get();
    $viewstype = ViewsType::latest()->get();

    // 取得網址上的 type 與 region 參數，若沒有則預設為 'all'
    $selectedType = $request->query('type', 'all');
    $selectedRegion = $request->query('region', 'all');

    return view('front.views.views', compact('views', 'viewstype', 'selectedType', 'selectedRegion'));
});

// 詳細頁面路由 (需確保有設定)
Route::get('/views/{id}', function ($id) {
    // 詳細頁邏輯...
})->name('views.show');


Route::get("/views/{id}", [FrontViewsController::class, "detail"]);
