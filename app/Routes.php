<?php

return [
  ['GET', '/', ['Crawler\Controllers\Homepage', 'show']],
  ['GET', '/readme', ['Crawler\Controllers\Readme', 'show']],
  ['GET', '/parsefiles', ['Crawler\Controllers\Parser', 'show']],
];
