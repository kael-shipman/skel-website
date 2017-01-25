<?php
namespace Ks;

class Cms extends \Skel\Cms {
  public function getContentFileFromPath(string $path) {
    $path = trim($path, '/');
    if ($path == '') $path = 'home';
    return $this->config->getDbContentRoot()."/pages/$vars.md";
  }
}

