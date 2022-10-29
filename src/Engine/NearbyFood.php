<?php

declare(strict_types=1);

namespace DaveSnake\Engine;

use DaveSnake\Models\BattleSnake;
use DaveSnake\Models\Board;
use DaveSnake\Models\Coordinates;
use DaveSnake\Models\Move;

class NearbyFood
{
    private Board $board;
    private BattleSnake $me;

    public function __construct($data)
    {
        $this->board = new Board($data->board);
        $this->me = new BattleSnake($data->you);
    }

    public function findFood(array $possibleMoves, int $range)
    {
        // Look around the immediate area
        for ($distance = 0; $distance <= $range; $distance++) {
            foreach ($possibleMoves as $move) {
                if ($this->checkForFood($this->getTarget($move, $distance))) {
                    error_log("[NearbyFood] Found food " . $distance . " spaces away");
                    return $move;
                }
            }
        }

        // nothing in range, where the hell is the food?
        error_log("[NearbyFood] Nothing nearby, looking around");
        if (count($this->board->food)) {
            $foodDistances = [];

            foreach ($this->board->food as $food) {
                $distancex = $this->me->head->x - $food->x;
                $distancey = $this->me->head->y - $food->y;

                if ($distancex < 0) {
                    $distancex *= -1;
                }
                if ($distancey < 0) {
                    $distancey *= -1;
                }

                array_push($foodDistances, (object)[
                    "x" => $food->x,
                    "y" => $food->y,
                    "distance" => $distancex + $distancey,
                ]);
            }

            usort($foodDistances, function ($a, $b) {
                return $a->distance <=> $b->distance;
            });

            // error_log("[NearbyFood] " . print_r($foodDistances, true));

            $closest = new Coordinates($foodDistances[0]);

            error_log("[NearbyFood] Next closest " . print_r($closest, true));

            $targetDirections = [];

            if ($closest->x < $this->me->head->x) {
                array_push($targetDirections, Move::$LEFT);
            }

            if ($closest->x > $this->me->head->x) {
                array_push($targetDirections, Move::$RIGHT);
            }

            if ($closest->y < $this->me->head->y) {
                array_push($targetDirections, Move::$DOWN);
            }

            if ($closest->y > $this->me->head->y) {
                array_push($targetDirections, Move::$UP);
            }

            $targetDirections = array_values(array_intersect($targetDirections, $possibleMoves));
            
            if (count($targetDirections)) {
              error_log("[NearbyFood] Nothing close by, heading further afield " . $targetDirections[0]);
              return $targetDirections[0];
            }
        }

        error_log("[NearbyFood] No food!");
        return false;
    }

    public function getTarget(string $move, int $distance): Coordinates
    {
        $x = 0;
        $y = 0;

        switch($move) {
            case Move::$UP:
                $x = $this->me->head->x;
                $y = $this->me->head->y + $distance;
                break;
            case Move::$DOWN:
                $x = $this->me->head->x;
                $y = $this->me->head->y - $distance;
                break;
            case Move::$LEFT:
                $x = $this->me->head->x - $distance;
                $y = $this->me->head->y;
                break;
            case Move::$RIGHT:
                $x = $this->me->head->x + $distance;
                $y = $this->me->head->y;
                break;
        }

        return new Coordinates((object) [ "x" => $x, "y" => $y]);
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