<?php

namespace App\Server\System\Components\Site;

use App\System\Components\AbstractFactoryPagesClass\AbstractFactoryPagesClass;
use App\System\Components\Base\Base;
use App\System\Util;
use simplehtmldom\HtmlWeb;


class Site extends Base implements ISite
{
  /**
   * @var string
   */
  public string $url;
  
  /**
   * @var string
   */
  public string $PageClass;
  
  /**
   * @var array
   */
  public array $list;
  
  /**
   * @param array $parameters
   * @throws \Exception
   */
  public function __construct(array $parameters = [])
  {
    $this->setDebugCliMessage('Site ' . Util::get_strict_option($parameters, 'url', 'string'));
    
    parent::__construct($parameters);
    
    if (empty($this->getUrl())) {
      throw new \Exception('Url is empty');
    }
    if (empty($this->getPageClass())) {
      throw new \Exception('PageClass ' . $this->getPageClass() . ' for ' . $this->getUrl() . ' is empty');
    }
    if (empty($this->getList())) {
      throw new \Exception('List for ' . $this->getUrl() . ' and PageClass ' . $this->getPageClass() . ' is empty');
    }
  }
  
  /**
   * @return void
   */
  public function execute(): void
  {
    // Pages
    foreach ($this->getList() as $config) {
      if (!Util::get_strict_option($config, 'is_enabled', 'bool')) continue;
      
      $web = new HtmlWeb();
      
      $dom = $web->load($this->getUrl() . Util::get_strict_option($config, 'page', 'string'));
      
      $config['dom'] = $dom;
      $config['debug_cli'] = $this->getParameter('debug_cli');
      $config['PageClass'] = $this->getParameter('PageClass');
      $config['url'] = $this->getParameter('url');
      
      $page = AbstractFactoryPagesClass::create($this->getPageClass(), [$config]);
      
      $page->run();
      
      $page->save();
    }
  }
  
  /**
   * @return string
   */
  public function getUrl(): string
  {
    return $this->url;
  }
  
  /**
   * @return string
   */
  public function getPageClass(): string
  {
    return $this->PageClass;
  }
  
  /**
   * @return array
   */
  public function getList(): array
  {
    return $this->list;
  }
}