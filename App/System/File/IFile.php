<?php

namespace App\System\File;

interface IFile
{
  public function get();
  
  public function set();
  
  public function create();
}