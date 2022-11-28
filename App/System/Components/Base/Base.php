<?php

namespace App\System\Components\Base;


use App\System\Util;

class Base implements IBase
{
  /**
   * @var string
   */
  public string $id;
  
  /**
   * @var array
   */
  private array $_attributeNames;
  
  /**
   * @var array
   */
  public array $parameters;
  
  /**
   * @var bool
   */
  public bool|null $debug_cli = false;
  
  /**
   * @var string
   */
  public string $debug_cli_message = '';
  
  /**
   * @var array
   */
  public array $cmd_options = [];
  
  public function __construct(array $config)
  {
    $this->setDebugCli(Util::get_strict_option($config, 'debug_cli', 'bool'));
    
    $this->initAttributeNames();
    
    if (is_array($config) && !empty($config)) {
      $this->setAttributes($config);
      $this->setParameters($config);
      
      $this->showDebugCli();
    }
  }
  
  /**
   * @return string
   */
  public function getId(): string
  {
    return $this->id;
  }
  
  /**
   * @param string $id
   */
  public function setId(string $id): void
  {
    $this->id = $id;
  }
  
  /**
   * Check for public properties
   * @return void
   */
  private function initAttributeNames()
  {
    $reflect = new \ReflectionClass($this);
    
    $props = $reflect->getProperties(\ReflectionProperty::IS_PUBLIC);
    
    $this->_attributeNames = array_map(function ($prop) {
      return $prop->getName();
    }, $props);
  }
  
  /**
   * Get names of public properties
   * @return array
   */
  public function getAttributeNames()
  {
    return $this->_attributeNames;
  }
  
  /**
   * Set public properties
   * @param array $attributes
   * @return void
   */
  public function setAttributes($attributes)
  {
    foreach ($this->getAttributeNames() as $name) {
      if (array_key_exists($name, $attributes)) {
        $this->$name = $attributes[$name];
      }
    }
  }
  
  /**
   * Get public attributes
   * @return array [name => value]
   */
  public function getAttributes()
  {
    $attribures = [];
    
    foreach ($this->getAttributeNames() as $name) {
      $attribures[$name] = $this->$name;
    }
    
    return $attribures;
  }
  
  /**
   * @return array
   */
  public function getParameters(): array
  {
    return $this->parameters;
  }
  
  /**
   * @param string $key
   * @return mixed
   */
  public function getParameter(string $key): mixed
  {
    $parameters = $this->getParameters();
    
    return isset($parameters[$key]) && !empty($parameters[$key]) ? $parameters[$key] : null;
  }
  
  /**
   * @param array $parameters
   */
  public function setParameters(array $parameters): void
  {
    $this->parameters = $parameters;
  }
  
  /**
   * @param bool|null $debug_cli
   * @return void
   */
  public function setDebugCli(bool|null $debug_cli): void
  {
    $this->debug_cli = $debug_cli;
  }
  
  /**
   * @return bool|null
   */
  public function isDebugCli(): bool|null
  {
    return $this->debug_cli;
  }
  
  public function showDebugCli(string $debug_message = ''): void
  {
    if ($this->isDebugCli()) {
      echo '[ Server ] [ ' . date('Y-m-d H:i:s') . ' ] - ' . (!empty($debug_message) ? $debug_message : $this->getDebugCliMessage()) . PHP_EOL;
    }
  }
  
  /**
   * @return string
   */
  public function getDebugCliMessage(): string
  {
    return $this->debug_cli_message;
  }
  
  /**
   * @param string $debug_cli_message
   */
  public function setDebugCliMessage(string $debug_cli_message): void
  {
    $this->debug_cli_message = $debug_cli_message;
  }
  
  
}