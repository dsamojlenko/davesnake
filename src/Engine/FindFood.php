<?php

declare(strict_types=1);

namespace DaveSnake\Engine;

use DaveSnake\Models\BattleSnake;
use DaveSnake\Models\Board;
use DaveSnake\Models\Coordinates;

class FindFood
{
    private Board $board;
    private BattleSnake $me;

    public function __construct($data)
    {
        $this->board = new Board($data->board);
        $this->me = new BattleSnake($data->you);
    }

    public function findFood(string $move, int $distance): bool
    {
        $x = 0;
        $y = 0;

        switch($move) {
            case 'up':
                $x = $this->me->head->x;
                $y = $this->me->head->y + $distance;
                break;
            case 'down':
                $x = $this->me->head->x;
                $y = $this->me->head->y - $distance;
                break;
            case 'left':
                $x = $this->me->head->x - $distance;
                $y = $this->me->head->y;
                break;
            case 'right':
                $x = $this->me->head->x + $distance;
                $y = $this->me->head->y;
                break;
        }

        $target =  new Coordinates((object) [ "x" => $x, "y" => $y]);

        return $this->checkForFood($target);
    }

    public function checkForFood(Coordinates $target): bool
    {
        foreach($this->board->food as $food) {
            if ($food->x == $target->x && $food->y == $target->y) {
                return true;
            }
        }
        return false;
    }
}