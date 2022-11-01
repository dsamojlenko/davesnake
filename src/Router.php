<?php

declare(strict_types=1);

namespace DaveSnake;

use DaveSnake\Engine\Engine;

class Router
{
    private $request;

    public function __construct(RequestInterface $request)
    {
        $this->request = $request;
        $this->bootstrapRoutes();
    }

    public function bootstrapRoutes()
    {
        switch($this->request->REQUEST_URI) {
            case "/":
                $apiversion = "1";
                $author     = "dsamojlenko";
                $color      = getenv("SNAKE_COLOUR") ?: "#900C3F";
                $head       = getenv("SNAKE_HEAD") ?: "smile";
                $tail       = getenv("SNAKE_TAIL") ?: "bolt";

                Api::indexResponse($apiversion,$author,$color,$head, $tail);
                break;
            case "/start":
                // read the incoming request body stream and decode the JSON
                $data = json_decode(file_get_contents('php://input'));

                // TODO - if you have a stateful snake, you could do initialization work here
                Api::startResponse();
                break;
            case "/move":
                //Move Section          
                $data = $this->request->getBody();
                // $engine = new Engine($data);
                $engine = new Engine2\Engine($data);
                $move = $engine->getMove();

                return Api::moveResponse($move);
                break;
            case "/end":
                // read the incoming request body stream and decode the JSON
                $data = json_decode(file_get_contents('php://input'));

                // TODO - if you have a stateful snake, you could do finalize work here
                Api::endResponse();
                break;
            default:
                header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found');
        }
    }

}