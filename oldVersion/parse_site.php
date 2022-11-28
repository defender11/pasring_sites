#!/usr/bin/php

<?php

require 'vendor/autoload.php';

use simplehtmldom\HtmlWeb;

/**
 * @param \simplehtmldom\HtmlNode $post
 * @param string $site_url
 * @return array
 */
function get_forpost_news_content(simplehtmldom\HtmlNode $post, string $site_url): array
{
  $site = ' 🌍 ' . $site_url;
  $title = ' 📰 ' . trim($post->find('.post-header .media-body h5 a', 0)->plaintext);
  $date = trim($post->find('.post-header .media-body p small', 0)->plaintext);
  $head = $title . ' | ' . $date;
  $link = trim($post->find('.post-header .dropdown-menu .dropdown-item .px-2', 0)->getAttribute('href'));
  $icon = trim($post->find('.post-header .media a img', 0)->getAttribute('src'));
  $description = ' 💬 ' . str_replace('</b></i></b></i>', '', trim($post->find('.post-body .indent-for-blocks', 0)->plaintext));

//  $str = PHP_EOL;
//  $str .= $site . PHP_EOL;
  $str = $head . PHP_EOL;
  $str .= $description . PHP_EOL;

  return [
    'site' => $site,
    'title' => $title,
    'date' => $date,
    'head' => $head,
    'link' => $link,
    'icon' => $icon,
    'description' => $description,
    'cli' => $str
  ];
}

/**
 * @param array $cmdOption
 * @param array $contents
 * @return void
 */
function show_news(array $cmdOption, array $contents)
{
  if (get_cmd_option($cmdOption, 'notify', 'bool')) {
    system('notify-send "' . $contents['head'] . '" "' . $contents['description'] . '" ');
  }

  if (get_cmd_option($cmdOption, 'cli', 'bool')) {
    echo <<<STRING
    {$contents['cli']}
    STRING;
  }
}

/**
 * @param array $cmdOption
 * @param string $key
 * @param string $expected_type
 * @return mixed
 */
function get_cmd_option(array $cmdOption, string $key, string $expected_type): mixed
{
  $var = isset($cmdOption[$key]) && !empty($cmdOption[$key]) ? $cmdOption[$key] : null;

  if (is_null($var)) {
    return null;
  }

  return match ($expected_type) {
    'int' => (int)$var,
    'bool' => (bool)$var,
    'array' => (array)$var,
    'string', 'default' => (string)$var,
  };
}

function get_data(string $file, string $key = ''): mixed
{
  $path = __DIR__ . '/' . $file;
  if (file_exists($path)) {
    $raw_data = file_get_contents($path);
    $data = json_decode($raw_data, true);
    
    return !empty($key) ? $data[$key] : $data;
  }

  return [];
}

/**
 * @param string $file
 * @return void
 */
function create_file_if_not_exists(string $file, array $data = [])
{
  $path = __DIR__ . '/' . $file;
  
  if (!file_exists($path)) {
    try {
      file_put_contents($path, $data);
    } catch (Exception $e) {
      echo $e->getMessage();
    }
  }
}

function set_data(string $file, array $data = []): bool
{
  $path = __DIR__ . '/' . $file;
  if (file_exists($path)) {
    file_put_contents($path, json_encode($data));
    return true;
  }

  return false;
}

function forpost_news(array $cmdOption, string $tg_chanel, $config)
{
  $storage_name = 'storage.json';
  
  $render_type = 'cli';
  
  if (get_cmd_option($cmdOption, 'notify', 'bool')) {
    $render_type = 'notify';
  }

  $data[$tg_chanel][$render_type] = [];
  
  create_file_if_not_exists($storage_name, $data);
  
  if (get_cmd_option($cmdOption, 'clear-once-list', 'bool')) {
    set_data($storage_name);
    exit();
  }

  $site_url = 'https://tgstat.ru/en/channel/@' . $tg_chanel;

  $countShow = get_cmd_option($cmdOption, 'count', 'int') ?? $config['count_news'];

  $reading_post_ids = [];

  if (get_cmd_option($cmdOption, 'show-once', 'bool')) {
    $storage = get_data($storage_name);
    $reading_post_ids = $storage[$tg_chanel][$render_type]['reading_posts_ids'];
  }

  $html = (new HtmlWeb())->load($site_url);

  if (!is_null($html)) {
    $posts_list = $html->find('.post-container');

    foreach ($posts_list as $number => $post) {

      if ($number >= $countShow) break;

      $post_id = $post->getAttribute('id');

      $contents = get_forpost_news_content($post, $site_url);

      if (get_cmd_option($cmdOption, 'show-once', 'bool')) {
        if (!isset($reading_post_ids[$post_id])) {
          show_news($cmdOption, $contents);
        }

        $storage[$tg_chanel][$render_type]['reading_posts_ids'][$post_id] = true;
      } else {
        show_news($cmdOption, $contents);
      }
    }

    if (
      !empty($storage[$tg_chanel][$render_type]['reading_posts_ids']) &&
      get_cmd_option($cmdOption, 'show-once', 'bool')
    ) {
      set_data($storage_name, $storage);
    }
  }
}

$config = get_data('config.json');

$cmdOption = getopt("", $config['cmd_options']);

if (!empty($cmdOption)) {
  foreach ($config['tg_channels'] as $tg_chanel_name => $tg_chanel) {
    if ($tg_chanel['is_enabled']) {
      forpost_news($cmdOption, $tg_chanel_name, $config);
    }
  }
}

