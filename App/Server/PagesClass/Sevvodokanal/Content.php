<?php

namespace App\Server\PagesClass\Sevvodokanal;

use App\Server\System\Components\Content\IContent;
use \App\Server\System\Components\Content\Content as BaseContent;
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
    
    $site = $this->getParameter('url');
    $link = $site . trim($post->find('.img-preview a', 0)->getAttribute('href'));
    
    $link_parts = explode('/', $link);
    $id = array_pop($link_parts);
    
    $this->setPostId($id);
    $this->setPost($post);
    
    $page = new HtmlWeb();
    
    $current_page_news = $page->load($link);
    
    $title = trim($current_page_news->find('#news-preview .right-preview h1', 0)->plaintext);
    $date = trim($current_page_news->find('#news-preview .right-preview span', 0)->plaintext);
    $head = $title . ' | ' . $date;
    $icon = trim($current_page_news->find('#news-preview .img-preview img', 0)->getAttribute('src'));
    $description = str_replace('</b></i></b></i>', '', trim($current_page_news->find('#news-preview .right-preview .text-preview', 0)->plaintext));
    
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