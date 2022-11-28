<?php

namespace App\System;

use App\System\Components\Base\Base;

class Cleaner extends Base
{
  public function __construct(array $parameters = [], array $cmd_option = [])
  {
    $this->setDebugCliMessage('Cleaner started');
    
    parent::__construct($parameters);
  }
  
  public function execute()
  {
    $config = \App\System\Config::get_instance()->get();
    $diff_day = $this->getParameter('diff_cleaning_day') ?: $config['diff_cleaning_day'];
    $this->oldMessages($diff_day);
  }
  
  /**
   * @param int $diff_day
   * @return void
   */
  public function oldMessages(int $diff_day): void
  {
    $storage = Storage::get_instance()->get();
    
    if (!empty($storage)) {
      foreach ($storage as $site => $list) {
        foreach ($list as $page => $data) {
          $storage[$site][$page]['items'] = array_filter($data['items'], function ($item) use ($diff_day) {
            
            $datetime_created = str_replace('__', ' ', $item['created']['human']);
            $raw_datetime_created = explode(' ', $datetime_created);
            $date_created = date_create($raw_datetime_created[0]);
            $date_now = date_create(date('Y-m-d'));
            
            return date_diff($date_created, $date_now)->days <= $diff_day;
          });
        }
      }
      
      Util::updateDataFiles($storage);
    }
  }
}