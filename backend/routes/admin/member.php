<?php

use App\Http\Controllers\Admin\MemberController;
use Illuminate\Support\Facades\Route;

Route::group(["prefix" => "admin"], function () {
    Route::group(["prefix" => "member", "middleware" => "manager"], function () {
        Route::get("list", [MemberController::class, "list"]);
        Route::get("create", [MemberController::class, "create"]);
        Route::post("store", [MemberController::class, "store"]);
        Route::get("edit/{id}", [MemberController::class, "edit"]);
        Route::post("update", [MemberController::class, "update"]);
        Route::post("delete", [MemberController::class, "delete"]);
        Route::get("getMembers", [MemberController::class, "getMembers"]);
    });
});
