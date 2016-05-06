<?php

namespace Crawler\Controllers;

use Http\Request;
use Http\Response;
use Crawler\Parser\Files;
use Crawler\Parser\CurlList;

class Parser
{

  private $request;
  private $response;
  private $files;
  private $curl;

  public function __construct(Request $request, Response $response, Files $files, CurlList $curl)
  {
    $this->request = $request;
    $this->response = $response;
    $this->files = $files;
    $this->curl = $curl;
    $this->curl->setBaseUrl('www.buhv.de');
  }

  public function show()
  {

    $this->response->setContent($content);
  }

  public function run()
  {
    $this->response->setHeader('Content-Type', 'application/json');
    $content = $this->curl->runSingleUrl('http://www.buhv.de/gemeindepaedagogik/Themenhefte-Gemeinde.html');
    $this->response->setContent($content);
  }

  public function single()
  {
    $url = $this->request->getParameter('url');
    $content = $this->curl->runSingleUrl($url);
    $this->response->setHeader('Content-Type', 'application/json');
    $this->response->setContent($content);
  }

  public function buildlist()
  {
    $content = $this->files->getUrls();
    $this->response->setHeader('Content-Type', 'application/json');
    $this->response->setContent($content);
  }

  public function cleanupfile()
  {
    $url = $this->request->getParameter('url');
    $content = $this->curl->cleanUpWorkfile($url);
    $this->response->setHeader('Content-Type', 'application/json');
    $this->response->setContent($content);
  }
}
