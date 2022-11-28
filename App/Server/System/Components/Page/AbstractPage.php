<?php

namespace App\Server\System\Components\Page;

use App\System\Components\Base\Base;
use App\System\Storage;
use App\System\Util;

abstract class AbstractPage extends Base
{
  public object|null $content = null;
  
  public function save()
  {
    $posts = $this->getPosts();
    
    $storage = Storage::get_instance()->get();
    
    $PageClass = $this->getParameter('PageClass');
    
    if (!empty($posts)) {
      
      foreach ($posts as $content) {
        if (!isset($storage[$PageClass][$this->getId()])) {
          $storage[$PageClass][$this->getId()] = [
            'new' => 0,
            'items' => [],
          ];
        }
        
        $storage[$PageClass][$this->getId()]['items'][$content->getPostId()] = [
          'page_id' => $this->getId(),
          'post_id' => $content->getPostId(),
          'data' => base64_encode(json_encode($content->get())),
          'is_reading' => false,
          
          'page_class' => $PageClass,
          
          'encode' => 'base64/json',
          
          'created' => [
            'machine' => time(),
            'human' => date('Y-m-d__H:i:s'),
          ],
        ];
      }
  
      Util::updateDataFiles($storage);
    }
  }
}