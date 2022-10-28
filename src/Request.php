<?php

declare(strict_types=1);

namespace DaveSnake;

class Request implements RequestInterface
{
    public function __construct()
    {
        $this->bootstrap();
    }

    private function bootstrap()
    {
        foreach($_SERVER as $key => $value)
        {
            $this->{$key} = $value;
        }
    }
    public function getBody()
    {
        if($this->REQUEST_METHOD === "GET")
        {
            return;
        }


        if ($this->REQUEST_METHOD == "POST")
        {
          return json_decode(file_get_contents('php://input'));
        }
    }
}