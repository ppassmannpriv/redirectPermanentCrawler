<?php

namespace Crawler\Controllers;

use Http\Request;
use Http\Response;

class Homepage
{

  private $request;
  private $response;

  public function __construct(Request $request, Response $response)
  {
    $this->request = $request;
    $this->response = $response;
  }

  public function show()
  {
    $content = '<h1>Crawler - Read me</h1>';
    $content .= '<p>Dude, like get some documentation here - the whole application scaffolding is way to much. DI, and controller logic and whoops and what not? come on dude.</p>';
    $this->response->setContent($content);
  }

}
