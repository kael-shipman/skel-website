<?php
namespace Ks;

class App extends \Skel\App {
  protected $cms;

  public function setCms(\Skel\Interfaces\Cms $cms) {
    $this->cms = $cms;
    return $this;
  }







  public function getPage(array $vars=array()) {
    $mainContent = $this->cms->getContentForPath('/'.implode('/',$vars));
    if (!$mainContent) throw new \Skel\Http404Exception();

    $siteComponent = Components\SiteComponent::create($this);
    $mainContent->setTemplate($this->getTemplate('PageComponent.php'));
    $siteComponent['mainContent'] = $mainContent;

    $mainContent['context'] = $this;

    // Abandonment notice
    $mainContent['content'] = "<blockquote style=\"border: 2px solid #fe0; border-radius: 5px; margin: 10px; padding: 20px;\">\n<h3 style=\"margin: 0 0 10px;\">Project Abandoned</h3>\n\n<p style=\"font-weight: bold\">Warning! This project was really fun and a learned a lot, but it has now been abandoned. Feel free to rummage around here among the bones, but don't expect to find anything living here!</p>\n</blockquote>\n\n".$mainContent['content'];

    // Conditional content

    if ($vars['section'] == 'docs') {
      $toc = $this->cms->getContentForPath('/docs/00-toc');
      $mainContent['content'] = $mainContent['content']."\n\n-------------\n\n### Table of Contents\n\n".$toc['content'];
      unset($toc);
    }

    if (strpos($mainContent['content'], '##whatIsSkel##') !== false) {
      $whatIsSkel = file_get_contents($this->cms->getContentFileFromPath('/snippets/what-is-skel'));
      $mainContent['content'] = str_replace('##whatIsSkel##', $whatIsSkel, $mainContent['content']);
    }

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







  // Overrides

  public function getTemplate(string $name) {
    $path = $this->config->getTemplateDir()."/$name";
    $type = substr($path, strrpos($path, '.')+1);
    if ($type == 'html') $t = new \Ks\StringTemplate($path);
    elseif ($type == 'php') $t = new \Skel\PowerTemplate($path);
    else $t = parent::getTemplate($name);
    return $t;
  }
}

