<?php
namespace Ks;

class App extends \Skel\App {
  protected $cms;

  public function setCms(\Skel\Interfaces\Cms $cms) {
    $this->cms = $cms;
    return $this;
  }







  public function getPage(array $vars=array()) {
    $file = $this->cms->getContentFileFromPath('/'.implode('/',$vars));
    if (!$file || !file_exists($file)) throw new \Skel\Http404Exception();

    $siteComponent = Components\SiteComponent::create($this);
    $contentSync = new \Skel\ContentSynchronizerLib($this->config, $this->db, $this->cms);
    $mainContent = $contentSync->getObjectFromFile($file);
    $mainContent->setTemplate($this->getTemplate('PageComponent.php'));
    $siteComponent['mainContent'] = $mainContent;

    $mainContent['context'] = $this;
    $siteComponent['context'] = $this;

    return $siteComponent;
  }

  public function getFormattedContent(string $content) {
    $pde = new \Ks\ParsedownExtra();
    return $pde->text($content);
  }

  public function getMenu(string $name) {
    $items = $this->db->getMenuItems($name);
    foreach($items as $uri => $title) $items[$uri] = '<a href="'.$uri.'" class="menu-item">'.$title.'</a>';
    return $items;
  }






  public function prepareUiForError(\Skel\Interfaces\App $app, \Skel\Interfaces\Component $c, int $errCode) {
    return true;
  }
}

