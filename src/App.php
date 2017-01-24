<?php
namespace Ks;

class App extends \Skel\App {
  protected $cms;

  public function setCms(\Skel\Interfaces\Cms $cms) {
    $this->cms = $cms;
    return $this;
  }

  public function getPage(array $vars=array()) {
    $file = $this->getFileFromVars($vars);
    if (!$file) throw new \Skel\Http404Exception();

    $contentSync = new \Skel\ContentSynchronizerLib($this->config, $this->db, $this->cms);
    $mainContent = $contentSync->getObjectFromFile($file);

    $pde = new \Ks\ParsedownExtra();
    $mainContent['content'] = $pde->text($mainContent['content']);
    $mainContent->setTemplate(new \Skel\StringTemplate('<h1>##title##</h1>##content##', false));

    return $mainContent;
  }






  public function prepareUiForError(\Skel\Interfaces\App $app, \Skel\Interfaces\Component $c, int $errCode) {
    return true;
  }

  protected function getFileFromVars($vars) {
    if (count($vars) == 0) $vars = 'home';
    else $vars = implode('/', $vars);
    return $this->config->getContentPagesDir()."/$vars.md";
  }
}

