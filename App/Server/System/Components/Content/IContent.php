<?php

namespace App\Server\System\Components\Content;


/**
 * @method @parsing()
 * @method @get()
 */
interface IContent
{
  public function parsing(\simplehtmldom\HtmlNode $post);
  
  public function get();
}