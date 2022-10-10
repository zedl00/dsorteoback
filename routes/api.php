<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Endpoints : Auth

Route::group(["prefix" => "v1/auth"], function () {
   Route::post("login", [AuthController::class, "ingresar"]);
   Route::post("registro", [AuthController::class, "registrar"]);

   Route::group(["middleware" => "auth:sanctum"], function () {
      Route::get("perfil", [AuthController::class, "perfil"]);
      Route::post("salir", [AuthController::class, "logout"]);
   });
});
