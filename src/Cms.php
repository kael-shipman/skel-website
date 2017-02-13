<?php
namespace Ks;

class Cms extends \Skel\Cms {
  protected $contentSyncLib;

  public function getContentForPath(string $path) {
    $file = $this->getContentFileFromPath($path);
    if (!file_exists($file)) return null;

    $contentSync = $this->getContentSyncLib();
    return $contentSync->getObjectFromFile($file);
  }

  public function getContentFileFromPath(string $path) {
    $path = trim($path, '/');
    if ($path == '') $path = 'home';
    return $this->config->getDbContentRoot()."/pages/$path.md";
  }

  protected function getContentSyncLib() {
    if (!$this->contentSyncLib) $this->contentSyncLib = new \Skel\ContentSynchronizerLib($this->config, new ContentSyncDbStub(), $this);
    return $this->contentSyncLib;
  }
}

