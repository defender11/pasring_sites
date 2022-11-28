<?php

namespace App\System\Components\Stats;

use App\System\File\File;
use App\System\File\IFile;
use App\System\Util;

class Stats implements IStats
{
  /**
   * @var IStats|null
   */
  private static IStats|null $instance = null;
  
  private static string $file_name = '';
  
  private static IFile|null $file;
  
  /**
   * @var string
   */
  public static string $error = '';
  
  /**
   * Get instance of class, create if needed
   * @return static
   */
  public static function get_instance(): IStats
  {
    if (self::$instance === null) {
      self::$instance = new static();
  
      $files_name = \App\System\Config::get_instance()->get('files');
       
      self::$instance::$file_name = Util::get_strict_option($files_name, 'stats', 'string');
      
      self::$instance::$file = new File(self::$instance::$file_name);
    }
    return self::$instance;
  }
  
  /**
   * @return void
   */
  public static function update()
  {
    $storage = \App\System\Storage::get_instance()->get();
    
    $file_text = '';
    
    if (!empty($storage)) {
      foreach ($storage as $site => $list) {
  
        $pages = [];
  
        foreach ($list as $page => $store) {
          
          $count = self::$instance::calculate_items($store['items']);
          $storage[$site][$page]['new'] = $count;
  
          $pages[] = $page . ': ' . $count;
        }
  
        $file_text .= $site . ': [ ' . implode(' | ', $pages)  . ' ]' . PHP_EOL;
      }
    }
    
    self::get_instance()::$file->set($file_text, true);
    
    \App\System\Storage::get_instance()->set($storage);
  }
  
  private static function calculate_items(array $items): int
  {
    $count = 0;
  
    foreach ($items as $item) {
      if (!$item['is_reading']) ++$count;
    }
    
    return $count;
  }
}

