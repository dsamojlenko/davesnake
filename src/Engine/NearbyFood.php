<?php

declare(strict_types=1);

namespace DaveSnake\Engine;

use DaveSnake\Engine\Concerns\IdentifiesLocations;
use DaveSnake\Models\BattleSnake;
use DaveSnake\Models\Board;
use DaveSnake\Models\Coordinates;

class NearbyFood
{
    private Board $board;
    private BattleSnake $me;

    use IdentifiesLocations;

    public function __construct($data)
    {
        $this->board = new Board($data->board);
        $this->me = new BattleSnake($data->you);
    }

    public function findFood(array $possibleMoves, int $radius = 0)
    {
        error_log("[NearbyFood] Looking for food");
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

            $closest = new Coordinates($foodDistances[0]);
            error_log("[NearbyFood] Closest " . print_r($closest, true));

            if ($foodDistances[0]->distance < $radius || $radius === 0) {
                $targetDirections = array_values(array_intersect($this->getTargetDirections($closest), $possibleMoves));

                if (count($targetDirections)) {
                  error_log("[NearbyFood] Nothing close by, heading further afield " . $targetDirections[0]);
                  return $targetDirections[0];
                }
            }
            error_log("[NearbyFood] No food within radius");
            return false;
        }

        error_log("[NearbyFood] No food!");
        return false;
    }
}