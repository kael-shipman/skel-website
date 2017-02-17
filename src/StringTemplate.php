<?php
namespace Ks;

class StringTemplate extends \Skel\StringTemplate {
  public function render(array $elmts) {
    return str_replace('\\#', '#', parent::render($elmts));
  }
}
