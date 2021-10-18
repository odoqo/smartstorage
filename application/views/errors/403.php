<?php

$host = 'http://'.$_SERVER['HTTP_HOST'].'/';
header('HTTP/1.1 403 Forbidden');
header("Status: 403 Forbidden");
header('Location:'.$host.'403');