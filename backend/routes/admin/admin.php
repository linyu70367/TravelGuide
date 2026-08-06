<?php

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Route;

Route::get("/admin/home", [AdminController::class, "home"])->middleware("manager");
Route::get("/admin", [AdminController::class, "login"]);
Route::post("/admin/login", [AdminController::class, "doLogin"]);
Route::get("/admin/viewstype", function () {
    return view("admin.views.viewstype");
});
Route::delete("/admin/delete", [AdminController::class, "delete"]);
