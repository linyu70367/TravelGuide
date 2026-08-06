<?php

use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\Views\FrontViewsController;
use App\Http\Controllers\ViewsController;
use App\Models\Img;
use App\Models\Views;
use App\Models\ViewsType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

include "travelfood.php";
include "views.php";
include "member.php";


// Route::get('/', function () {
//     // 查詢景點資料
//     $recentViews = Views::with(['imgs', 'types'])
//         ->latest()
//         ->take(6)
//         ->get();
//     $response = Http::get('https://data.moa.gov.tw/Service/OpenData/ODwsv/ODwsvTravelFood.aspx?IsTransData=1&UnitId=193');
//     $foods = $response->json() ?? [];

//     // 渲染
//     return view('front.home', compact('recentViews','foods'));
// });

Route::get('/', [HomeController::class, "tfv"]);

Route::get('/about', function () {
    return view('front.about');
});
