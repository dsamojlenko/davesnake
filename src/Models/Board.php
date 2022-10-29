<?php

declare(strict_types=1);

namespace DaveSnake\Models;

class Board
{
    public int $height;
    public int $width;
    public array $food;
    public array $hazards;
    public array $snakes;

    public function __construct(object $data)
    {
        $this->height = $data->height;
        $this->width = $data->width;
        $this->food = $data->food;
        $this->hazards = $data->hazards;
        $this->snakes = $data->snakes;
    }
}