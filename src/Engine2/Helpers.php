<?php


namespace DaveSnake\Engine2;


use DaveSnake\Models\BattleSnake;
use DaveSnake\Models\Board;

class Helpers
{

    public BattleSnake $me;
    public Board $board;

    public function __construct($data)
    {
        $this->me = new BattleSnake($data->you);
        $this->board = new Board($data->board);
    }

    public function getTargetPosition(object $head, string $move)
    {
        switch($move) {
            case "up":
                return (object)[
                    "x" => $head->x,
                    "y" => $head->y +1,
                ];
            case "down":
                return (object)[
                    "x" => $head->x,
                    "y" => $head->y -1,
                ];
            case "left":
                return (object)[
                    "x" => $head->x -1,
                    "y" => $head->y,
                ];
            case "right":
                return (object)[
                    "x" => $head->x +1,
                    "y" => $head->y,
                ];
        }
    }

    public function findTargetInArray(object $target, array $locations): bool
    {
        foreach($locations as $location)
        {
            if ($location->x === $target->x && $location->y === $target->y) {
                return true;
            }
        }
        return false;
    }

    public function getAdjacentCells(object $target, array $except = []): array
    {
        $adjacentCells = [];

        if(!in_array("left", $except)) {
            if ($target->x - 1 >= 0) {
                $adjacentCells["left"] = (object)[
                    "x" => $target->x - 1,
                    "y" => $target->y
                ];
            }
        }

        if(!in_array("right", $except)) {
            if ($target->x + 1 < $this->board->width) {
                $adjacentCells["right"] = (object)[
                    "x" => $target->x + 1,
                    "y" => $target->y
                ];
            }
        }

        if(!in_array("down", $except)) {
            if ($target->y - 1 >= 0) {
                $adjacentCells["down"] = (object)[
                    "x" => $target->x,
                    "y" => $target->y - 1
                ];
            }
        }

        if(!in_array("up", $except)) {
            if ($target->y + 1 < $this->board->height) {
                $adjacentCells["up"] = (object)[
                    "x" => $target->x,
                    "y" => $target->y + 1
                ];
            }
        }

        return $adjacentCells;
    }
}