<?php

define('PACKAGEPATH', __DIR__);
define('WEBURL', "http://{$_SERVER['HTTP_HOST']}");

include(PACKAGEPATH . '/Boot.php');
include(PACKAGEPATH . '/packages/CSSPackage.php');
include(PACKAGEPATH . '/packages/JSPackage.php');
