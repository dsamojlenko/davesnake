<?php


namespace DaveSnake\Engine;


use DaveSnake\Models\Coordinates;

class AvoidTraps extends AvoidanceBaseClass implements AvoidanceInterface
{

    public function filterMoves($possibleMoves): array
    {
        error_log("[AvoidTraps] Looking ahead to check for exits");
        foreach($possibleMoves as $move) {
            $target = $this->getMoveTargetCoordinates($move, 1);

            $allHazards = [];
            foreach($this->board->snakes as $snake) {
                $allHazards = array_merge($allHazards, $snake->body);
            }

            $adjacentCells = $this->board->getAdjacentCells($target);
            $adjacentCells = array_values(array_map(function($coordinate) {
                return (object)[
                    "x" => $coordinate->x,
                    "y" => $coordinate->y,
                ];
            }, $adjacentCells));

            // $boardHazards = [...$this->board->hazards];
            $allHazards = array_merge($allHazards, $this->board->hazards);

            $intersection = array_uintersect($allHazards, $adjacentCells, function($a, $b) {
                return strcmp(spl_object_hash($a), spl_object_hash($b));
            });

            error_log("[AvoidTraps] intersection: " . print_r($intersection, true));

            if(!empty($intersection)) {
                $possibleMoves = $this->eliminateMove($possibleMoves, $move);
            }
        }

        return $possibleMoves;
    }
}