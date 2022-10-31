<?php

use DaveSnake\Request;
use DaveSnake\Router;

require __DIR__.'/vendor/autoload.php';

try {
    $dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__);
    $dotenv->load();
} catch (Exception $e) {
    // do nothing
}

$router = new Router(new Request);
