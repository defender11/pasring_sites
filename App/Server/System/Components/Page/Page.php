<?php

namespace App\Server\System\Components\Page;


use App\System\Components\AbstractFactoryPagesClass\AbstractFactoryPagesClass;
use App\System\Storage;
use App\System\Util;

class Page extends AbstractPage
{
  public \simplehtmldom\HtmlDocument $dom;
  
  public int $count_items = 0;
  
  public int $count_parsed = 0;
  
  public array $posts = [];
  
  public array|\simplehtmldom\HtmlNode $dom_posts;
  
  public function __construct(array $parameters = [])
  {
    $this->setDebugCliMessage('Page ' . Util::get_strict_option($parameters, 'page', 'string'));
    
    parent::__construct($parameters);
  }
  
  public function run()
  {
    $list = [];
    
    if (!empty($this->getDomPosts())) {
      
      $storage = Storage::get_instance()->get();
      $PageClass = $this->getParameter('PageClass');
      
      foreach ($this->getDomPosts() as $number => $post) {
        
        if ($number >= $this->getCountItems()) break;
        
        $content = AbstractFactoryPagesClass::create($PageClass, [$this->getDom(), $this->getParameters()], 'server', 'Content');
         
        $content->parsing($post);
        
        if (!empty($content->getPostId())) {
          $is_need_save = true;
          
          if (
            !empty($storage) &&
            isset($storage[$PageClass][$this->getId()]) &&
            isset($storage[$PageClass][$this->getId()]['items'][$content->getPostId()]) &&
            $storage[$PageClass][$this->getId()]['items'][$content->getPostId()]['is_reading']
          ) {
            $is_need_save = false;
          }
          
          if ($is_need_save) {
            $list[$content->getPostId()] = $content;
          }
        }
      }
    }
    
    if (!empty($list)) {
      $this->setPosts($list);
    }
  }
  
  /**
   * @return object
   */
  public function getDom(): object
  {
    return $this->dom;
  }
  
  /**
   * @return int
   */
  public function getCountItems(): int
  {
    return $this->count_items;
  }
  
  /**
   * @return array
   */
  public function getPosts(): array
  {
    return $this->posts;
  }
  
  /**
   * @param array $posts
   */
  public function setPosts(array $posts): void
  {
    $this->posts = $posts;
  }
  
  /**
   * @return array|\simplehtmldom\HtmlNode
   */
  public function getDomPosts(): array|\simplehtmldom\HtmlNode
  {
    return $this->dom_posts;
  }
  
  /**
   * @param array|\simplehtmldom\HtmlNode $dom_posts
   * @return void
   */
  public function setDomPosts(array|\simplehtmldom\HtmlNode $dom_posts): void
  {
    $this->dom_posts = $dom_posts;
  }
  
  /**
   * @return int
   */
  public function getCountParsed(): int
  {
    return $this->count_parsed;
  }
  
  /**
   * @return void
   */
  public function incrementCountParsed(): void
  {
    ++$this->count_parsed;
  }
}