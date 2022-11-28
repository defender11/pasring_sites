<?php

namespace App\Server\PagesClass\Sevenergosbyt;

use App\Server\System\Components\Content\IContent;
use \App\Server\System\Components\Content\Content as BaseContent;
use App\System\Util;
use simplehtmldom\HtmlWeb;

class Content extends BaseContent implements IContent
{
  /**
   * @return array
   */
  public function get()
  {
    return $this->getContent();
  }
  
  /**
   * @param \simplehtmldom\HtmlNode $post
   * @return void
   */
  public function parsing(\simplehtmldom\HtmlNode $post): void
  {
    $link = trim($post->find('.s-blog-item--news .s-blog-item__more a', 0)->getAttribute('href'));
    $id = str_replace('https://sevenergosbyt.ru/?p=', '', $link);
    
    $this->setPostId($id);
    $this->setPost($post);
    
    $page = new HtmlWeb();
  
    $current_page_news = $page->load($link);
  
    $site = $this->getParameter('url');
    $title = preg_replace('/\s/', ' ', trim($current_page_news->find('.post-content h1', 0)->plaintext));
    $date = trim($current_page_news->find('.post-content .s-blog-item__date', 0)->plaintext);
    $head = $title . ' | ' . $date;
    $icon = '';
    $description = str_replace('</b></i></b></i>', '', trim($current_page_news->find('.post-content .entry-content', 0)->plaintext));
    
    $str = $head . PHP_EOL;
    $str .= $description . PHP_EOL;
    
    $content = [
      'page_id' => $this->getId(),
      'post_id' => $this->getPostId(),
      'site' => $site,
      'title' => $title,
      'date' => $date,
      'head' => $head,
      'link' => $link,
      'icon' => $icon,
      'description' => $description,
      'cli' => $str,
      
      'created' => [
        'machine' => time(),
        'human' => date('Y-m-d__H:i:s'),
      ]
    ];
    
    $this->setContent($content);
  }
}