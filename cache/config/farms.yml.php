<?php

// WARNING: file automatically generated: do not modify.

return array (
  'gnospedia' => 
  array (
    'server' => '(?P<wiki>[a-z0-9-]+)\\.localhost:4000',
    'variables' => 
    array (
      0 => 
      array (
        'variable' => 'wiki',
        'default' => '$wiki',
      ),
    ),
    'suffix' => 'wiki',
    'wikiID' => '$wiki',
    'data' => '/Users/mohitkumar/Downloads/PProjects/wikipedia_v2/GnosPedia/cache/wikis/$wiki',
    'config' => 
    array (
      0 => 
      array (
        'file' => 'LocalSettings.php',
        'executable' => true,
      ),
      1 => 
      array (
        'file' => 'FarmSettings.php',
        'executable' => true,
      ),
    ),
  ),
  'main' => 
  array (
    'server' => 'localhost:4000',
    'suffix' => 'main',
    'wikiID' => 'main',
    'data' => '/Users/mohitkumar/Downloads/PProjects/wikipedia_v2/GnosPedia/cache',
    'config' => 
    array (
      0 => 
      array (
        'file' => 'LocalSettings.php',
        'executable' => true,
      ),
    ),
  ),
);