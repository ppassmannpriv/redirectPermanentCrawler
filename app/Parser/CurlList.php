<?php
namespace Crawler\Parser;

require __DIR__.'/../../vendor/autoload.php';

use \Curl\MultiCurl;
use \Curl\Curl;

class CurlList
{
  private $urlJson = false;
  private $files = false;
  private $errors = false;
  private $successes = false;
  private $baseUrl = false;

  public function __construct(Files $files)
  {
    $this->files = $files;
    $this->urlJson = $files->getUrls();
  }

  public function setBaseUrl($url)
  {
    $this->baseUrl = $url;
  }

  public function runAllUrls()
  {
    $this->multiCurlScaffolding();

    return array(
       'successes' => $this->successes,
       'errors' => $this->errors
    );
  }

  public function runSingleUrl($url)
  {
    $result = $this->runHeaderOnly($url);
    $resultArray = json_decode($result, true);
    if($resultArray['success'] != true) {

    }
    return $result;
  }

  public function runSingleUrls()
  {
    $return = false;
    foreach(json_decode($this->urlJson) as $url)
    {
      $return .= $this->runSingleUrl($url);
    }

    return $return;
  }

  private function runHeaderOnly($url)
  {
      $headers = get_headers($url, 1);

      if(is_array($headers) && in_array('HTTP/1.1 301 Moved Permanently', $headers))
      {

        $url = $headers['Location'];
        if(strpos($url, $this->baseUrl) === false)
        {
          $url = $this->baseUrl.$url;
        }

        $headers = json_decode($this->runHeaderOnly(str_replace('Location: ', '', $url)), true);

      }
      if($headers !== false){
        if(is_array($headers) && in_array('HTTP/1.1 200 OK', $headers))
        {
          return json_encode(array('success' => true, 'url' => $url, 'status' => 'HTTP/1.1 200 OK'));
        } else {
          return json_encode(array('success' => false, 'url' => $url, 'debug' => $headers));
        }
      } else {
          return json_encode(array('success' => 'warning', 'url' => $url, 'debug' => false));
      }
  }

  private function runSingleCurls()
  {
    foreach(json_decode($this->urlJson) as $url)
    {
      $curl = new Curl();
      $curl->setOpt(CURLOPT_FOLLOWLOCATION, 1);
      $curl->setOpt(CURLOPT_NOBODY, 1);
      $curl->setOpt(CURLOPT_HEADER, 1);
      $curl->get($url);

      var_dump($curl->response);
    }
  }

  private function testSingleCurl()
  {
      $curl = new Curl();
      $curl->setOpt(CURLOPT_FOLLOWLOCATION, 1);
      $curl->setOpt(CURLOPT_NOBODY, 1);
      $curl->setOpt(CURLOPT_HEADER, 1);
      $curl->get('http://www.buhv.de/kirche/material-oeffentlichkeitsarbeit/pfarrbriefmantel-gemeindebriefmantel/farbmaentel.html');

      var_dump($curl->response);
  }

  private function multiCurlScaffolding()
  {
    $multiCurl = New MultiCurl();

    $multiCurl->success(function($instance){
      $this->curlSuccess($instance);
    });
    $multiCurl->error(function($instance) {
      $this->curlError($instance);
    });
    $multiCurl->complete(function($instance) {
      $this->curlComplete($instance);
    });

    foreach(json_decode($this->urlJson) as $url)
    {
      $multiCurl->addGet($url);
    }
    $multiCurl->setOpt(CURLOPT_FOLLOWLOCATION, 1);
    $multiCurl->setOpt(CURLOPT_NOBODY, 1);
    $multiCurl->setOpt(CURLOPT_HEADER, 1);
    $multiCurl->start();

  }

  private function curlSuccess($instance)
  {
    echo '<span class="url success">'.$instance->url.'</span>';
  }

  private function curlError($instance)
  {
    echo '<span class="url error">'.$instance->url.' - <strong class="errorcode">'.$instance->errorCode.'</strong></span>';
  }

  private function curlComplete($instance)
  {
    echo '<hr class="complete" />';
  }

}
