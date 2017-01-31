<?php
namespace KS;

class SiteComponent extends \Skel\Component {
  public static function create(\Skel\Interfaces\App $app) {
    $c = new static(array('pageTitle' => '', 'content' => ''), $app->getTemplate('PageComponent.html'));
  }
}

