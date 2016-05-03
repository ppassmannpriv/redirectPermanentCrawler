<?php

namespace Crawler\Parser;

use InvalidArgumentException;

class FilesParser implements Files
{
  private $fileFolder = false;
  private $files = false;

  public function __construct($fileFolder) {
    if (!is_string($fileFolder)) {
        throw new InvalidArgumentException('pageFolder must be a string');
    }
    $this->fileFolder = $fileFolder;
  }

  public function getFiles()
  {
  
    if($handle = opendir($this->fileFolder))
    {
      while(false !== ($entry = readdir($handle))) {
        if($entry != '.' && $entry != '..') {
          $this->files[] = $entry;
        }
      }
      closedir($handle);
    }

    return $this->files;
  }

}
