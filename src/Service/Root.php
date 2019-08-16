<?php

namespace App\Service;

/**
* Provides the absolute path to the root directory via %kernel.project_dir% injected in services.yaml. 
*/
class Root 
{
  
  private $root;
  
  public function __construct(string $root) 
  {
    $this->root = $root;
  }
  
  public function getRootPath() 
  {
    return $this->root;
  }
  
}