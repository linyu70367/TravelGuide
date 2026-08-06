<?php

use App\Http\Controllers\Api\ApiWishlistController;
use Illuminate\Support\Facades\Route;

Route::group(["prefix" => "wishlist"], function () {
    Route::group(["middleware" => "web"], function () {
        Route::get("list", [ApiWishlistController::class, "list"]);
        Route::post("add", [ApiWishlistController::class, "add"]);
        Route::delete("delete", [ApiWishlistController::class, "delete"]);
        Route::get("checkLiked", [ApiWishlistController::class, "checkLiked"]);
    });
    Route::get("getLikes", [ApiWishlistController::class, "getLikes"]);
});
