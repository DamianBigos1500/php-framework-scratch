<?php

use Phplite\Router\Route;

Route::get('/user/{id}', 'HomeController@index');

// Route::any('/home', function () {
//   echo "test home anyF";
// });

Route::get('/home', 'HomeController@index');

Route::prefix('admin', function () {
  Route::middleware('Admin|Owner', function () {
    Route::get("dashboard", 'DashboardController@index');
    Route::get("users", 'UsersController@index');
    Route::get("admin", 'AdminController@index');
  });
});
