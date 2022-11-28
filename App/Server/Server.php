<?php


namespace App\Server;

use App\System\Components\Base\Base;
use App\Server\System\Components\Site\Site;

class Server extends Base
{
  private Site $site;
  
  public function __construct(array $parameters = [], array $cmd_option = [])
  {
    $this->setDebugCliMessage('Server started');
    
    parent::__construct($parameters);
    
    try {
      $this->site = new Site($this->getParameters());
    } catch (\Exception $e) {
      echo $e->getMessage();
    }
  }
  
  /**
   * @return void
   */
  public function execute(): void
  {
    $this->site->execute();
  }
}