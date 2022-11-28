<?php

namespace App\Server\PagesClass\TelegramChanelTGStat;

use App\Server\System\Components\Content\IContent;
use \App\Server\System\Components\Content\Content as BaseContent;

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
    $this->setPostId($post->getAttribute('id'));
    $this->setPost($post);
    
    $site = $this->getParameter('url');
    $title = trim($post->find('.post-header .media-body h5 a', 0)->plaintext);
    $date = trim($post->find('.post-header .media-body p small', 0)->plaintext);
    $head = $title . ' | ' . $date;
    $link = trim($post->find('.post-header .dropdown-menu .dropdown-item .px-2', 0)->getAttribute('href'));
    $icon = trim($post->find('.post-header .media a img', 0)->getAttribute('src'));
    $description = str_replace('</b></i></b></i>', '', trim($post->find('.post-body .indent-for-blocks', 0)->plaintext));
    
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