<?php

namespace App\Client;

use App\System\Components\AbstractFactoryPagesClass\AbstractFactoryPagesClass;
use App\System\Components\Base\Base;
use App\System\Util;

class Client extends Base
{
  public function __construct(array $parameters = [], array $cmd_option = [])
  {
    $this->setDebugCliMessage('Client started');
    
    parent::__construct($parameters);
  }
  
  /**
   * @return void
   */
  public function execute(): void
  {
    $config = \App\System\Config::get_instance()->get();
    $storage = \App\System\Storage::get_instance()->get();
    
    if (!empty($storage)) {
      foreach ($storage as $site => $list) {
        foreach ($list as $page => $store) {
          foreach ($store['items'] as $item) {
            if ($item['is_reading']) continue;
        
            $item['debug_cli'] = $config['debug_cli'];
            $item['output'] = !empty($this->getParameter('output')) ? $this->getParameter('output') : 'cli';
        
            $content = AbstractFactoryPagesClass::create($item['page_class'], [$item], 'client', 'Content');
        
            $content->show();
        
            $storage[$site][$item['page_id']]['items'][$item['post_id']]['is_reading'] = true;
          }
        }
      }
  
      Util::updateDataFiles($storage);
    }
  }
}