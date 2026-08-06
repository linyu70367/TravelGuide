<?php

use App\Http\Controllers\Api\ApiMemberController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Front\MemberController as fm;  // frontmember 跟adminmember區隔開

Route::group(["prefix" => "member"], function () {
    Route::group(['middleware' => 'guest'], function () {
        Route::get("login", [fm::class, "login"])->name('member.login');
        Route::get("register", [fm::class, "register"]);
    });
    Route::middleware('auth')->group(function () {
        Route::get("home", [fm::class, "home"])->name("member.home");
        Route::post("logout", [fm::class, "logout"]);
    });
    Route::post("doLogin", [fm::class, "doLogin"]);
    Route::post("store", [fm::class, "store"]);
});
