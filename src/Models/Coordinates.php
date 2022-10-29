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

    public function getLocationId(Coordinates $coordinates, Board $board)
    {
        if($fullRows = $coordinates->y) {
            return ($fullRows * $board->width) + $coordinates->x + 1;
        }
        return $coordinates->x + 1;
    }
}