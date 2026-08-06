<?php

use App\Http\Controllers\ViewsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// 1. 取得文章列表 (GET http://localhost/api/posts)
Route::get('/views', [ViewsController::class, 'index']);

//瀏覽次數前10多的文章列表
Route::get('/views10', [ViewsController::class, 'like10']);

//瀏覽次數和類別的文章列表
Route::get('/views&types', [ViewsController::class, 'viewsandtype']);

// 2. 新增文章 (POST http://localhost/api/posts)
Route::post('/views', [ViewsController::class, 'store']);

// 6. 呼叫getViews
Route::get('/views/getView', [ViewsController::class, 'getView']);

// 讀取景點以及收藏
Route::get("/views/getWishView", [ViewsController::class, "getWishView"]);

// 3. 取得單篇文章 (GET http://localhost/api/posts/1)
Route::get('/views/{id}', [ViewsController::class, 'show']);

// 4. 更新文章 (PUT http://localhost/api/posts/1)
Route::put('/views/{id}', [ViewsController::class, 'update']);

// 4. 更新文章 (PUT http://localhost/api/posts/1)
Route::patch('/views/patch/{id}', [ViewsController::class, 'patch']);

// 5. 刪除文章 (DELETE http://localhost/api/posts/1)
Route::delete('/views/{id}', [ViewsController::class, 'destroy']);
