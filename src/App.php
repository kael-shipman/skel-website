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

    return $siteComponent;
  }

  public function getFormattedContent(string $content) {
    $pde = new \Ks\ParsedownExtra();
    return $pde->text($content);
  }

  public function getMenu(string $name) {
    $currentUri = $this->request->getUri();
    $items = $this->db->getMenuItems($name);
    foreach($items as $uri => $title) {
      $selected = (preg_match('#^'.$uri.'($|/)#', $currentUri->getPath()) ? 'selected' : '');
      $items[$uri] = '<a href="'.$uri.'" class="menu-item '.$selected.'">'.$title.'</a>';
      $submenu = $this->db->getMenuItems($uri);
      if (count($submenu) > 0 && $uri != '/') {
        $subitems = array();
        foreach($submenu as $subUri => $subtitle) {
          $selected = (preg_match('#^'.$subUri.'($|/)#', $currentUri->getPath()) ? 'selected' : '');
          $submenu[$subUri] = '<a href="'.$subUri.'" class="menu-item '.$selected.'">'.$subtitle.'</a>';
        }
        $items[$uri] .= "\n        <div class=\"submenu\">\n          ".implode("\n          ", $submenu)."\n        </div>";
      }
    }
    return $items;
  }






  public function prepareSiteComponent(\Skel\Interfaces\App $app, \Skel\Interfaces\Component $c) {
    if (!($c instanceof \Ks\Components\SiteComponent)) return true;
    $c['context'] = $this;
    $c['siteTitle'] = $c['mainContent']['title'].' | '.$c['siteTitle'];
    return true;
  }

  public function prepareUiForError(\Skel\Interfaces\App $app, \Skel\Interfaces\Component $c, int $errCode) {
    return true;
  }
}

