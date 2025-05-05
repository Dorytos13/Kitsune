<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
  return view('test');
});

// Catch-all route pour l'application Vue
Route::get('/{any}', function () {
  return view('test');
})->where('any', '.*');