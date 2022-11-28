<?php

namespace App\System;

class Config
{
  /**
   * @var File\IFile|null
   */
  private static \App\System\File\IFile|null $instance = null;
  
  /**
   * @var string
   */
  public static string $error = '';
  
  /**
   * Get instance of class, create if needed
   * @return static
   */
  public static function get_instance(string $storage_name = 'config.json'): File\File
  {
    if (self::$instance === null) {
      self::$instance = new File\File($storage_name);
    }
    return self::$instance;
  }
}

