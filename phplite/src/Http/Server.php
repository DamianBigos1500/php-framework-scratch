<?php

namespace Phplite\Http;

class Server
{
  /**
   * Server constructor
   * */
  private function __construct()
  {
  }

  /**
   * Get all server data
   * 
   * @return array
   * */
  public static function all()
  {
    return $_SERVER;
  }

  /**
   * Check that server has the key
   * 
   * @param string $key
   * 
   * @return bool
   * */
  public static function has($key)
  {
    return isset($_SERVER[$key]);
  }

  /**
   * Get session by the given key
   * 
   * @param string $key
   * 
   * @return mixed
   * */
  public static function get($key)
  {
    return static::has($key) ? $_SERVER[$key] : null;
  }

  /**
   * Get path info for path
   * 
   * @param string $path
   * 
   * @return array
   * */
  public static function path_info($path)
  {
    return pathinfo($path);
  }
}
