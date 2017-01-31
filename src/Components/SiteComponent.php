<?php
namespace Ks\Components;

class SiteComponent extends \Skel\Component {
  public static function create(\Skel\Interfaces\App $app) {
    $path = trim($app->getRequest()->getUri()->getPath(), '/');
    if ($path == '') $path = 'home';
    $pathArray = explode('/',$path);

    $pageId = implode('_', $pathArray);

    $c = new static(array(), $app->getTemplate('SiteComponent.html'));
    $c['lang'] = 'en';
    $c['theme'] = 'ks1';
    $c['execProfile'] = ($app->getExecutionProfile() == \Ks\Config::PROFILE_BETA ? 'beta' : '');
    $c['siteTitle'] = $app->str('sitetitle');
    $c['metaTags'] = '';
    $c['ogTags'] = '';
    $c['css'] = '<link rel="stylesheet" type="text/css" href="/assets/css/main.css">';
    $c['pageId'] = $pageId;
    $c['pageClasses'] = implode(' ', $pathArray);
    $c['endmarkSvg'] = @file_get_contents($app->getPublicRoot().'/assets/imgs/endmark.svg');
    $c['bulletSvg'] = @file_get_contents($app->getPublicRoot().'/assets/imgs/bullet.svg');
    $c['mainMenuItems'] = implode("\n        ", $app->getMenu('/'));
    $c['pageWidgets'] = '';
    $c['javascript'] = '<script type="text/javascript" src="/assets/js/skel.js" id="skel-js"></script>'."\n".'<script type="text/javascript" src="/assets/js/init.js"></script>';

    return $c;
  }
}

