<?php

namespace Phplite\Bootstrap;

use Phplite\Exceptions\Whoops;
use Phplite\Cookie\Cookie;
use Phplite\Session\Session;

class App
{
  /**
   * App constructor
   * 
   * @return void
   */
  private function __construct()
  {
  }


  /**
   * App constructor
   * 
   * @return void
   */
  public static function run()
  {
    // Register whoops
    Whoops::handle();

    // Start session
    Session::start();

    Cookie::set('name', 'Damian');
    echo Cookie::get('name');




    echo "asdasdas";
  }
}
