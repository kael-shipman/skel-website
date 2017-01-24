<?php
namespace Ks;

class Db extends \Skel\Db implements \Skel\Interfaces\AppDb, \Skel\Interfaces\ContentSyncDb {
  const VERSION = 1;

  public function upgradeDatabase(int $from, int $to) {
    if ($from < 1 && $to >= 1) {
    }
  }

  public function downgradeDatabase(int $from, int $to) {
  }

  public function getTemplate(string $name) {
    $path = $this->config->getDbContentRoot()."/templates/$name";
    $type = substr($path, strrpos($path, '.')+1);
    if ($type == 'html') $t = new \Skel\StringTemplate($path);
    elseif ($type == 'php') $t = new \Skel\PowerTemplate($path);
    else throw new \RuntimeException("Template `$name` not found at `$path`!");
    return $t;
  }




  /***
   * Stubbing out ContentSyncDb methods that we don't need
   */

  public function getContentFileList() { return array(); }
  public function registerFileRename(string $prevPath, string $newPath) { }
  public function filePathIsUnique(\Skel\Interfaces\ContentFile $file) { return true; }
  public function fileContentIdIsUnique(\Skel\Interfaces\ContentFile $file) { return true; }
}

