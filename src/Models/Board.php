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

    public function getLocationIdFromCoordinates(Coordinates $coordinates): int
    {
        if($rows = $coordinates->y) {
            return ($rows * $this->width) + $coordinates->x + 1;
        }
        return $coordinates->x +1;
    }

    public function getCoordinatesFromLocationId(int $locationId): Coordinates
    {
        $x = (int)(($locationId -1) % $this->width);
        $y = (int)(floor(($locationId -1) / $this->width));

        return new Coordinates((object)[
            "x" => $x,
            "y" => $y,
        ]);
    }

    public function getAdjacentCells(Coordinates $target): array
    {
        $adjacentCells = [];

        if($target->x -1 >= 0) {
            $adjacentCells["left"] = new Coordinates((object)[
                "x" => $target->x -1,
                "y" => $target->y
            ]);
        }

        if($target->x +1 < $this->width) {
            $adjacentCells["right"] = new Coordinates((object)[
                "x" => $target->x +1,
                "y" => $target->y
            ]);
        }

        if($target->y -1 >= 0) {
            $adjacentCells["down"] = new Coordinates((object)[
                "x" => $target->x,
                "y" => $target->y -1
            ]);
        }

        if($target->y +1 < $this->height) {
            $adjacentCells["up"] = new Coordinates((object)[
                "x" => $target->x,
                "y" => $target->y +1
            ]);
        }

        return $adjacentCells;
    }
}