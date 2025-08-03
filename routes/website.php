<?php

use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return view('components.website.index');
});

Route::get('/web/privacy-policy', function () {
    return view('components.website.privacy-policy');
});

Route::get('/web/refund-policy', function () {
    return view('components.website.refund-policy');
});

Route::get('/web/terms-conditions', function () {
    return view('components.website.terms-conditions');
});
