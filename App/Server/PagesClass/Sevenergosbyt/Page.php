<?php

namespace App\Server\PagesClass\Sevenergosbyt;

use App\Server\System\Components\Page\IPage;

class Page extends \App\Server\System\Components\Page\Page implements IPage
{
  public function run()
  {
    if (!is_null($this->getDom())) {
      
      $posts = $this->dom->find('#main .col-lg-9 .row', 0)->childNodes();
      
      if (!empty($posts)) {
        $this->setDomPosts($posts);
        parent::run();
      }
    }
  }
}