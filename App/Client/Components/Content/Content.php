<?php

namespace App\Client\Components\Content;

use App\System\Components\Base\Base;

class Content extends Base
{
  /**
   * @var string
   */
  public string $page_id;
  
  /**
   * @var string
   */
  public string $post_id;
  
  /**
   * @var string
   */
  public string $data;
  
  public array|object|null $item = null;
  
  /**
   * @var bool
   */
  public bool $is_reading;
  
  /**
   * @var string
   */
  public string $encode;
  
  /**
   * @var array
   */
  public array $created;
  
  public function __construct(array $config)
  {
    $this->setDebugCliMessage('Get Content');
    
    parent::__construct($config);
  
    $this->setItem(
      json_decode(
        base64_decode($this->getData())
      )
    );
  }
  
  /**
   * @return array|object|null
   */
  public function getItem(): object|array|null
  {
    return $this->item;
  }
  
  /**
   * @param array|object|null $item
   */
  public function setItem(object|array|null $item): void
  {
    $this->item = $item;
  }
  
  /**
   * @return string
   */
  public function getPageId(): string
  {
    return $this->page_id;
  }
  
  /**
   * @return string
   */
  public function getPostId(): string
  {
    return $this->post_id;
  }
  
  /**
   * @return string
   */
  public function getData(): string
  {
    return $this->data;
  }
  
  /**
   * @return bool
   */
  public function isIsReading(): bool
  {
    return $this->is_reading;
  }
  
  /**
   * @return string
   */
  public function getEncode(): string
  {
    return $this->encode;
  }
  
  /**
   * @return array
   */
  public function getCreated(): array
  {
    return $this->created;
  }
  
  /**
   * @param string $data
   */
  public function setData(string $data): void
  {
    $this->data = $data;
  }
  
  /**
   * @param bool $is_reading
   */
  public function setIsReading(bool $is_reading): void
  {
    $this->is_reading = $is_reading;
  }
}