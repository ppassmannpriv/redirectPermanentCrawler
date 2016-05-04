<?php

namespace Crawler\Parser;

use InvalidArgumentException;

class FilesParser implements Files
{
  private $fileFolder = false;
  private $files = false;

  public function __construct($fileFolder) {
    if (!is_string($fileFolder)) {
        throw new InvalidArgumentException('fileFolder must be a string');
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

  public function getLines($files)
  {
    $lines = false;
    foreach($files as $file)
    {
      foreach(file($this->fileFolder.'/'.$file, FILE_IGNORE_NEW_LINES) as $line)
      {
        if($line != '')
        {
          $lines[] = $line;
        }
      }
    }

    return $lines;
  }

  public function parseFiles($files)
  {
    foreach($this->getLines($files) as $line)
    {
      $lines[] = $this->cleanLines($line);
    }
    $lines = array_unique($lines);
    return $lines;
  }

  private function cleanLines($input)
  {
    $output = str_replace('RedirectPermanent', '', $input);
    $output = explode('http://', $output);
    $output = 'http://'.end($output);
    if(!filter_var($output, FILTER_VALIDATE_URL) === false)
    {
      return $output;
    } else {
      return '';
    }
  }

  public function getUrls()
  {
    $lines = json_encode($this->parseFiles($this->getFiles()));

    return $lines;
  }

}
