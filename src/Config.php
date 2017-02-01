<?php
namespace Ks;

class Config extends \Skel\Config implements \Skel\Interfaces\AppConfig, \Skel\Interfaces\DbConfig, \Skel\Interfaces\ContentSyncConfig {
  public function getContextRoot() { return $this->get('context-root'); }
  public function getPublicRoot() { return $this->get('web-root'); }
  public function getDbPdo() { return $this->get('db'); }
  public function getDbContentRoot() { return $this->get('content-root'); }
  public function getContentPagesDir() { return $this->get('content-pages-dir'); }
  public function getTemplateDir() { return $this->get('template-dir'); }
}

