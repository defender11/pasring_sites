<?php

use App\System\Cleaner;
use App\Server\Server;
use App\Client\Client;

require 'vendor/autoload.php';

if (!defined('ROOT')) {
  define('ROOT', __DIR__);
}

\App\System\Paths::get_instance();

$config = \App\System\Config::get_instance()->get();
$storage = \App\System\Storage::get_instance()->get();

$cmd_option = getopt(
  "",
  \App\System\Util::get_strict_option($config, 'cmd_options', 'array')
);

if (!empty($cmd_option)) {
  $commands = [
    'client' => [
      'class' => Client::class,
    ],
    'server' => [
      'class' => Server::class,
    ],
    'cleaner' => [
      'class' => Cleaner::class,
    ],
  ];
  
  $proccess = \App\System\Util::get_strict_option($cmd_option, 'processing', 'string');
  
  if (isset($commands[$proccess])) {
    foreach (\App\System\Util::get_strict_option($config, 'sites', 'array') as $site) {
      $class = $commands[$proccess]['class'];
      $command = new $class(array_merge($site, $cmd_option));
      $command->execute();
    }
  }
}

exit();