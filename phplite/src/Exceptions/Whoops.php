<?php

namespace Phplite\Exceptions;

class Whoops
{
  /**
   * Whoops constructor
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
  public static function handle()
  {
    $whoops = new \Whoops\Run;
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->register();
  }
}
