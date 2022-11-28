<?php

namespace App\System;

class Paths
{
  /**
   * @var Paths|null
   */
  private static Paths|null $instance = null;
  
  /**
   * @var array
   */
  private static array $paths;
  
  /**
   * Get instance of class, create if needed
   * @return static
   */
  public static function get_instance(): Paths
  {
    if (self::$instance === null) {
      self::$instance = new static();
      self::init();
    }
    return self::$instance;
  }
  
  /**
   * @param string $key
   * @return string|array
   */
  public static function get(string $key = ''): string|array
  {
    return !empty($key) ? self::$paths[$key] : self::$paths;
  }
  
  public static function set(string $key, string|array $value): bool
  {
      if (!empty($value)) {
        self::$paths[$key] = $value;
        return true;
      }
      
      return false;
  }
  
  /**
   * @return void
   */
  private static function init(): void
  {
    $paths = \App\System\Config::get_instance()->get('paths');
    
    foreach ($paths as $name => $path) {
      if (!defined($name)) {
        
        $path_dir = ROOT . '/' . $path;
        
        define($name, $path_dir);
        
        self::get_instance()::set($name, $path_dir);
      }
    }
  }
}