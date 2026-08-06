<?php

use App\Http\Controllers\ViewsTypeController;
use Illuminate\Support\Facades\Route;

// 1. 取得列表
Route::get('/viewstype', [ViewsTypeController::class, 'index']);

// 2. 新增
Route::post('/viewstype', [ViewsTypeController::class, 'store']);

// 3. 更新
Route::put('/viewstype/{id}', [ViewsTypeController::class, 'update']);

// 4. 刪除
Route::delete('/viewstype/{id}', [ViewsTypeController::class, 'destroy']);
