<?php

namespace App\System\File;

class File implements IFile
{
  /**
   * @var string
   */
  private string $file_name;
  
  public function __construct(string $file_name)
  {
    $this->file_name = $file_name;
    
    if (empty($this->get())) {
      $this->create();
    }
  }
  
  public function get(string $key = ''): mixed
  {
    $path = ROOT . '/' . $this->file_name;
    
    if (file_exists($path)) {
      $raw_data = file_get_contents($path);
      $data = json_decode($raw_data, true);
      
      return !empty($key) && isset($data[$key]) ? $data[$key] : $data;
    }
    
    return [];
  }
  
  /**
   * @param array|string $data
   * @return bool
   */
  public function set(array|string $data = [], $as_string = false): bool
  {
    $path = ROOT . '/' . $this->file_name;
    
    if (file_exists($path)) {
      if ($as_string) {
        file_put_contents($path, $data);
      } else {
        file_put_contents($path, json_encode($data));
      }
      
      return true;
    }
    
    return false;
  }
  
  /**
   * @param array|string $data
   * @return bool
   */
  public function create(array|string $data = []): bool
  {
    $path = ROOT . '/' . $this->file_name;
    
    if (!file_exists($path)) {
      try {
        return (bool)file_put_contents($path, $data);
      } catch (\Exception $e) {
        echo $e->getMessage();
      }
    }
    return false;
  }
}