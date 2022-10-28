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
}