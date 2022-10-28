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
            $body = array();
            foreach($_POST as $key => $value)
            {
                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }

            return $body;
        }
    }
}