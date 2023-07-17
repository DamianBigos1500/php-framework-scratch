<?php


namespace App\Controllers;

use Phplite\Database\Database;
use Phplite\View\View;

class DashboardController
{
  public function index()
  {
    // return Database::query('SELECT * FROM users RIGHT JOIN roles ON roles.id=users.role_id LEFT JOIN roles ON roles.id=users.role_id');

    return Database::table("users")
      ->select("name", "age")
      ->rightJoin('roles', "roles.id", '=', 'users.role_id')
      ->leftJoin('roles', "roles.id", '=', 'users.role_id')
      ->join('roles', "roles.id", '=', 'users.role_id')
      ->where("id", "=", "20")
      ->orWhere("id", "!=", "20")
      ->orderBy("id")
      ->orderBy("name", "desc")
      ->getQuery();

    return View::render('admin.dashboard', ["name" => "Damian", "age" => "20"]);
    // return Url::path('user');
  }
}
