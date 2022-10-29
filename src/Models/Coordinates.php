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

    public function getLocationId(Board $board)
    {
        if($fullRows = $this->y) {
            return ($fullRows * $board->width) + $this->x + 1;
        }
        return $this->x + 1;
    }
}