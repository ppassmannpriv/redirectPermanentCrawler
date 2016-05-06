<?php

return [
  ['GET', '/', ['Crawler\Controllers\Homepage', 'show']],
  ['GET', '/readme', ['Crawler\Controllers\Readme', 'show']],
  ['GET', '/parsefiles', ['Crawler\Controllers\Parser', 'show']],
  ['GET', '/runparser', ['Crawler\Controllers\Parser', 'run']],
  ['GET', '/buildlist', ['Crawler\Controllers\Parser', 'buildlist']],
  ['POST', '/runsingle', ['Crawler\Controllers\Parser', 'single']],
  ['POST', '/cleanupfile', ['Crawler\Controllers\Parser', 'cleanupfile']]
];
