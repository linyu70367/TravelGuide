<?php

use App\Http\Controllers\Admin\AdminViewsController;
use Illuminate\Support\Facades\Route;

Route::group(["prefix" => "admin/views", "middleware" => "manager"], function () {
    Route::get("list", [AdminViewsController::class, "list"]);
    Route::get("add", [AdminViewsController::class, "add"]);
    Route::post("store", [AdminViewsController::class, "store"]);
    Route::get("edit/{id}", [AdminViewsController::class, "edit"]);
    Route::patch("update", [AdminViewsController::class, "update"]);
    Route::delete("delete", [AdminViewsController::class, "delete"]);
});
