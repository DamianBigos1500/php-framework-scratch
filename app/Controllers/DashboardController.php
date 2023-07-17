<?php


namespace App\Controllers;
use Phplite\Url\Url;

class DashboardController
{
  public function index()
  {
    return Url::path('user');
  }
}
