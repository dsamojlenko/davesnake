<?php

use DaveSnake\Request;
use DaveSnake\Router;

require __DIR__.'/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__);
$dotenv->load();

$router = new Router(new Request);
