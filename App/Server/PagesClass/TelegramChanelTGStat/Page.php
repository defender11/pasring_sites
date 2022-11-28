<?php

namespace App\Server\PagesClass\TelegramChanelTGStat;

use App\Server\System\Components\Page\IPage;

class Page extends \App\Server\System\Components\Page\Page implements IPage
{
  public function run()
  {
    if (!is_null($this->getDom())) {
      
      $posts = $this->dom->find('.post-container');
      
      if (!empty($posts)) {
        $this->setDomPosts($posts);
        parent::run();
      }
    }
  }
}