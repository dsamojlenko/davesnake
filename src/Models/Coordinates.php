<?php

declare(strict_types=1);

namespace DaveSnake\Models;

class Coordinates
{
    public int $x;
    public int $y;

    public function __construct(object $data)
    {  
      $this->x = $data->x;
      $this->y = $data->y;
    }
}