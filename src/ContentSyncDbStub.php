<?php
namespace Ks;

class ContentSyncDbStub implements \Skel\Interfaces\ContentSyncDb {

  public function getString(string $key) { return ''; }
  public function getStrings() { return array(); }
  public function getContentFileList() { return array(); }
  public function registerFileRename(string $prevPath, string $newPath) { }
  public function filePathIsUnique(\Skel\Interfaces\ContentFile $file) { return true; }
  public function fileContentIdIsUnique(\Skel\Interfaces\ContentFile $file) { return true; }

}

