<?php

use Illuminate\Support\Facades\Route;

Route::get('/example', function () {
    return response()->json(['message' => 'API Route Working']);
});
