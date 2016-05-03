<?php
require __DIR__.'/../../vendor/autoload.php';

class CreateUrlList
{
  private $filelist = false;
  private $urls = false;

  public function __construct(Filelist $files)
  {
    $this->filelist = $files->getFiles();
  }

  public function generateUrlList()
  {
    foreach($this->filelist as $fileObject)
    {
      return 1;
      //do stuff and put in $this->urls;
    }
  }

}
