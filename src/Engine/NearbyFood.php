<?php

declare(strict_types=1);

namespace DaveSnake\Engine;

use DaveSnake\Models\BattleSnake;
use DaveSnake\Models\Board;
use DaveSnake\Models\Coordinates;
use DaveSnake\Models\MoveTypes;

class NearbyFood
{
    private Board $board;
    private BattleSnake $me;

    public function __construct($data)
    {
        $this->board = new Board($data->board);
        $this->me = new BattleSnake($data->you);
    }

    public function findFood(array $possibleMoves)
    {
        // Look one space away
        foreach ($possibleMoves as $move) {
            if ($this->checkForFood($this->getTarget($move, 1))) {
                error_log("[NearbyFood] Found food one space away");
                return $move;
            }
        }

        // Look a little further away
        foreach ($possibleMoves as $move) {
            if ($this->checkForFood($this->getTarget($move, 2))) {
                error_log("[NearbyFood] Found food two spaces away");
                return $move;
            }
        }

        error_log("[NearbyFood] Nothing close by");
        return false;
    }

    public function getTarget(string $move, int $distance): Coordinates
    {
        $x = 0;
        $y = 0;

        switch($move) {
            case MoveTypes::$UP:
                $x = $this->me->head->x;
                $y = $this->me->head->y + $distance;
                break;
            case MoveTypes::$DOWN:
                $x = $this->me->head->x;
                $y = $this->me->head->y - $distance;
                break;
            case MoveTypes::$LEFT:
                $x = $this->me->head->x - $distance;
                $y = $this->me->head->y;
                break;
            case MoveTypes::$RIGHT:
                $x = $this->me->head->x + $distance;
                $y = $this->me->head->y;
                break;
        }

        $target =  new Coordinates((object) [ "x" => $x, "y" => $y]);

        return $target;
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