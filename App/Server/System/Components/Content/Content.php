<?php

namespace App\Server\System\Components\Content;


use App\System\Components\Base\Base;
use App\System\Util;

class Content extends Base
{
  public \simplehtmldom\HtmlDocument $dom;
  
  public array $content;
  
  public $post;
  
  public $post_id;
  
  public function __construct($dom, array $config)
  {
    $this->setDebugCliMessage('Get Content');
    parent::__construct($config);
    $this->setDom($dom);
  }
  
  /**
   * @return object|null
   */
  public function getDom(): ?object
  {
    return $this->dom;
  }
  
  /**
   * @param \simplehtmldom\HtmlDocument $dom
   */
  public function setDom(\simplehtmldom\HtmlDocument $dom): void
  {
    $this->dom = $dom;
  }
  
  /**
   * @return array
   */
  public function getContent(): array
  {
    return $this->content;
  }
  
  /**
   * @return mixed
   */
  public function getPost()
  {
    return $this->post;
  }
  
  
  /**
   * @param array $content
   */
  public function setContent(array $content): void
  {
    $this->content = $content;
  }
  
  /**
   * @param \simplehtmldom\HtmlNode $post
   * @return void
   */
  public function setPost(\simplehtmldom\HtmlNode $post): void
  {
    $this->post = $post;
  }
  
  /**
   * @return mixed
   */
  public function getPostId()
  {
    return $this->post_id;
  }
  
  /**
   * @param mixed $post_id
   */
  public function setPostId($post_id): void
  {
    $this->post_id = $post_id;
  }
}