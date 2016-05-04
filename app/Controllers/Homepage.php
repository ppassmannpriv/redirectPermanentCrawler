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
    $content = '
    <!DOCTYPE html>
    <html lang="en">
      <head>
        <title>Crawler</title>
        <!-- Required meta tags always come first -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="x-ua-compatible" content="ie=edge">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/css/bootstrap.min.css" integrity="sha384-y3tfxAZXuh4HwSYylfB+J125MxIs6mR5FOHamPBG064zB+AFeWH94NdvaCBm8qnd" crossorigin="anonymous">
        <link rel="stylesheet" href="/css/styles.css">
      </head>
      <body>
        <header>
          <div class="container-fluid">
            <div class="row">
              <div class="col-md-4">
                <h1>.htaccess Redirect Crawler</h1>
              </div>
            </div>
          </div>
        </header>
        <div id="main">
          <div class="container-fluid">
            <div class="row">
              <div class="col-md-8">
                <div id="viewer"></div>
              </div>
              <div class="col-md-4">
                <button type="button" id="get-urls" class="btn btn-primary">Get URLs</button>
                <button type="button" id="check-all-urls" class="btn btn-secondary btn-disabled">Check all URLs</button>
              </div>
            </div>
          </div>
        </div>
        <footer>
          <div class="container-fluid">
            <div class="row">
              <div class="col-md-12">2016</div>
            </div>
          </div>
        </footer>


        <!-- jQuery first, then Bootstrap JS. -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/js/bootstrap.min.js" integrity="sha384-vZ2WRJMwsjRMW/8U7i6PWi6AlO1L79snBrmgiDpgIWJ82z8eA5lenwvxbMV1PAh7" crossorigin="anonymous"></script>
        <script src="/script/app.js"></script>
      </body>
    </html>
    ';
    $this->response->setContent($content);
  }

}
