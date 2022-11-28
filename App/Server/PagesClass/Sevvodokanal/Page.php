<?php

namespace App\Server\PagesClass\Sevvodokanal;

use App\Server\System\Components\Page\IPage;

class Page extends \App\Server\System\Components\Page\Page implements IPage
{
  public function run()
  {
    if (!is_null($this->getDom())) {
      
      $posts = $this->dom->find('.interior #news-preview');
      
      if (!empty($posts)) {
        $this->setDomPosts($posts);
        parent::run();
      }
    }
  }
}