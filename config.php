<?php

$webRoot = getcwd();
$contextRoot = $webRoot.'/../';
$db = new PDO('sqlite:'.$contextRoot.'/db/skel.sqlite3');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

return array(
  'context-root' => $contextRoot, 
  'db' => $db,
  'cms-content-root' => $contextRoot.'/content',
  'exec-profile' => static::PROFILE_PROD,
  'content-root' => $contextRoot.'/content',
  'content-pages-dir' => $contextRoot.'/content/pages',
  'web-root' => $webRoot,
);

