<?php

$host = 'http://'.$_SERVER['HTTP_HOST'].'/';
header('HTTP/1.1 404 Not Found');
header("Status: 404 Not Found");
header('Location:'.$host.'404');
