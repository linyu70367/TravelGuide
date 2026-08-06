<?php

use App\Http\Controllers\ImgController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// 1. 取得文章列表 (GET http://localhost/api/posts)
Route::get('/imgs', [ImgController::class, 'index']);

// 2. 新增文章 (POST http://localhost/api/posts)
Route::post('/imgs', [ImgController::class, 'store']);

// 3. 取得單篇文章 (GET http://localhost/api/posts/1)
Route::get('/imgs/{id}', [ImgController::class, 'show']);

// 4. 更新文章 (PUT http://localhost/api/posts/1)
Route::put('/imgs/{id}', [ImgController::class, 'update']);

// 5. 刪除文章 (DELETE http://localhost/api/posts/1)
Route::delete('/imgs/{id}', [ImgController::class, 'destroy']);
