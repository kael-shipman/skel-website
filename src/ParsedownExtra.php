<?php
namespace Ks;

class ParsedownExtra extends \ParsedownExtra {
  public function text($text) {
    // Just do some basic replacements; nothin fancy
    return str_replace(array(' -- '), array('&mdash;'), parent::text($text));
  }
}
