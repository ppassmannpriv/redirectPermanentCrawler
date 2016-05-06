<?php

namespace Crawler\Parser;

use InvalidArgumentException;

class FilesParser implements Files
{
  private $fileFolder = false;
  private $resultFolder = false;
  private $workingDirectory = false;
  private $files = false;

  public function __construct($fileFolder, $resultFolder, $workingDirectory) {
    if (!is_string($fileFolder)) {
        throw new InvalidArgumentException('fileFolder must be a string');
    }
    $this->fileFolder = $fileFolder;
    $this->resultFolder = $resultFolder;
    $this->workingDirectory = $workingDirectory;
  }

  public function getFiles()
  {

    if($handle = opendir($this->fileFolder))
    {
      while(false !== ($entry = readdir($handle))) {
        if($entry != '.' && $entry != '..') {
          $this->files[] = $this->fileFolder.'/'.$entry;
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
      //foreach(file($this->fileFolder.'/'.$file, FILE_IGNORE_NEW_LINES) as $line)
      foreach(file($file, FILE_IGNORE_NEW_LINES) as $line)
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

  public function deleteUrlFromFile($url)
  {
    if(!is_dir($this->resultFolder)){
      mkdir($this->resultFolder);
    }

    foreach($this->getFiles() as $file)
    {
      $filename = end(explode('/', $file));

      if(!is_file($this->resultFolder.'/delete_'.$filename))
      {
        $newFile = fopen($this->resultFolder.'/delete_'.$filename, 'w');
        fclose($newFile);
      }
      if(is_file($this->resultFolder.'/keep_'.$filename))
      {
        $file = $this->resultFolder.'/keep_'.$filename;
      }
      $lines = false;
      foreach($this->getLines(array($file)) as $line)
      {
        if(strpos($line, $url) !== false){
          //log that this line has been deleted?
          file_put_contents($this->resultFolder.'/delete_'.$filename, $line.PHP_EOL, FILE_APPEND);
        } else {
          $lines[] = $line;
        }
      }

      file_put_contents($this->resultFolder.'/keep_'.$filename, implode(PHP_EOL, $lines));
    }

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

  private function strpos_arr($haystack, $needle)
  {
    if(!is_array($needle)) $needle = array($needle);
    foreach($needle as $what) {
      if(($pos = strpos($haystack, $what))!==false) return $pos;
    }
    return false;
  }

}
