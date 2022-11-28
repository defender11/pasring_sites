<?php

namespace App\System;

use App\System\Components\Stats\Stats;

class Util
{
  /**
   * @param array $data
   * @param string $key
   * @param string $expected_type
   * @return int|string|array|object|bool|null
   */
  public static function get_strict_option(array $data, string $key, string $expected_type): null|int|string|array|object|bool
  {
    $var = isset($data[$key]) && !empty($data[$key]) ? $data[$key] : null;
    
    if (is_null($var)) {
      return null;
    }
    
    return match ($expected_type) {
      'int' => (int)$var,
      'bool' => (bool)$var,
      'array' => (array)$var,
      'object' => (object)$var,
      'string', 'default' => (string)$var,
    };
  }
  
  /**
   * @param array $storage
   * @return void
   */
  public static function updateDataFiles(array $storage): void
  {
    Storage::get_instance()->set($storage);
    Stats::get_instance()::update();
  }
}