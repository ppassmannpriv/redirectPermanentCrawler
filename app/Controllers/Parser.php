<?php

namespace Crawler\Controllers;

use Http\Request;
use Http\Response;
use Crawler\Parser\Files;

class Parser
{

  private $request;
  private $response;
  private $files;

  public function __construct(Request $request, Response $response, Files $files)
  {
    $this->request = $request;
    $this->response = $response;
    $this->files = $files;
  }

  public function show()
  {
    $content = '';
    foreach($this->files->getFiles() as $file)
    {
      $content .= $file;
    }

    $this->response->setContent($content);
  }

}
