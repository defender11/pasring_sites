<?php

namespace App\System\Components\AbstractFactoryPagesClass;

class AbstractFactoryPagesClass
{
  public static function create(string $PageClass, array $parameters, string $side = 'server', string $class = 'Page'): object|null
  {
    if ($side === 'server') {
      $root_namespace = SERVER;
    }
    
    if ($side === 'client') {
      $root_namespace = CLIENT;
    }
    
    if (file_exists($root_namespace . '/PagesClass/' . $PageClass)) {
      $namespace = '\\App\\' . ucfirst($side) . '\\PagesClass\\' . $PageClass . '\\';
      
      $class = $namespace . $class;
      
      return new $class(...$parameters);
    }
    
    return null;
  }
}