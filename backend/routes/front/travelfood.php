<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;


Route::get('/travelfood', function () {
    $response = Http::get(
        'https://data.moa.gov.tw/Service/OpenData/ODwsv/ODwsvTravelFood.aspx?IsTransData=1&UnitId=193'
    );

    $foods = collect($response->json() ?? [])
        ->values()
        ->map(function ($food, $index) {
            $food['id'] = $index + 1;

            return $food;
        })
        ->all();

    return view('front.travelfood.travelfood', compact('foods'));
});

Route::get('/travelfood/{id}', function ($id) {
    return view('front.travelfood.travelfood_detail', compact('id'));
})
    ->whereNumber('id')
    ->name('travelfood.show');
