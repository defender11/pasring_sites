<?php

namespace App\Client\PagesClass\Sevenergosbyt;

use App\Client\Components\Content\IContent;
use \App\Client\Components\Content\Content as BaseContent;

class Content extends BaseContent implements IContent
{
  public function show()
  {
    if ($this->getParameter('output') === 'cli') {
      $this->renderCli();
    }
    
    if ($this->getParameter('output') === 'notify') {
      $this->renderNotify();
    }
  }
  
  private function renderCli()
  {
    $content = $this->getItem();
  
    echo <<<STRING
    -----------------------------------
    | 🔗 $content->link
    | 📰 $content->date | $content->title
    |----------------------------------
    | 💬 $content->description
    -----------------------------------
    STRING;
  }
  
  private function renderNotify()
  {
    $content = $this->getItem();
    
    system('notify-send "' . $content->head . '" "' . $content->description . '" ');
  }
}