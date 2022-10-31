<?php

declare(strict_types=1);

namespace DaveSnake\Engine;

use DaveSnake\Engine\Concerns\InteractsWithBoard;
use DaveSnake\Models\BattleSnake;
use DaveSnake\Models\Board;
use DaveSnake\Models\Coordinates;

class FoodFinder
{
    private Board $board;
    private BattleSnake $me;

    use InteractsWithBoard;

    public function __construct($data)
    {
        $this->board = new Board($data->board);
        $this->me = new BattleSnake($data->you);
    }

    public function findAdjacentFood(array $possibleMoves)
    {
        $food = array_map(function($location) {
            return new Coordinates($location);
        }, $this->getAvailableFood());

        $head = new Coordinates($this->me->head);

        $adjacents = $this->board->getAdjacentCells($head);

        foreach($possibleMoves as $move) {
            if (in_array($adjacents[$move], $food)) {
                return $move;
            }
        }

        return false;
    }

    public function findFoodInRadius(array $possibleMoves, int $radius = 0)
    {
        error_log("[NearbyFood] Looking for food");
        if (count($this->board->food)) {
            $foodTargets = $this->getFoodTargets();

            $closest = new Coordinates($foodTargets[0]);
            // error_log("[NearbyFood] Closest " . print_r($closest, true));

            if ($foodTargets[0]->distance < $radius || $radius === 0) {
                $targetDirections = array_values(array_intersect($this->getDirectionsToTarget($closest), $possibleMoves));

                if (count($targetDirections)) {
                  // error_log("[NearbyFood] Nothing close by, heading further afield " . $targetDirections[0]);
                  return $targetDirections[0];
                }
            }
            error_log("[NearbyFood] No food within radius");
            return false;
        }

        error_log("[NearbyFood] No food!");
        return false;
    }

    public function getAvailableFood()
    {
        return $this->board->food;
    }

    /**
     * @return array
     */
    public function getFoodTargets(): array
    {
        $foodTargets = [];

        foreach ($this->board->food as $food) {
            array_push($foodTargets, (object)[
                "x" => $food->x,
                "y" => $food->y,
                "distance" => $this->getDistanceToTarget(new Coordinates($food)),
            ]);
        }

        usort($foodTargets, function ($a, $b) {
            return $a->distance <=> $b->distance;
        });
        return $foodTargets;
    }
}