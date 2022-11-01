<?php


namespace DaveSnake\Engine;


use DaveSnake\Models\Coordinates;

class AvoidTraps extends AvoidanceBaseClass implements AvoidanceInterface
{

    public function checkNextMove(Coordinates $target)
    {
        // get cells adjacent to target
        $adjacentCells = array_map(function($cell) {
            return $this->board->getLocationIdFromCoordinates($cell);
        }, $this->board->getAdjacentCells($target));

        error_log("[AvoidTraps] adjacentCells: " . print_r($adjacentCells, true));

        // get all hazards
        $allHazards = array_map(function($hazard) {
            return $this->board->getLocationIdFromCoordinates(new Coordinates($hazard));
        }, $this->getAllHazards());

        error_log("[AvoidTraps] allHazards: " . print_r($allHazards, true));

        $potentialMoves = [];
        foreach($adjacentCells as $move => $position) {
            if (!in_array($position, $allHazards)) {
                array_push($potentialMoves, $move);
            }
        }

        return $potentialMoves;
    }

    public function filterMoves($possibleMoves): array
    {
        error_log("[AvoidTraps] Looking ahead to check for exits");

        foreach($possibleMoves as $move) {
            error_log("[AvoidTraps] Checking " . $move);

            // select a target
            $target = $this->getMoveTargetCoordinates($move, 1);

            $potentialMoves = $this->checkNextMove($target);

            if(count($potentialMoves) === 1) {
                $potentialMoves = $this->checkNextMove($this->getMoveTargetCoordinates(end($potentialMoves), 1));
            }

            if(!$potentialMoves) {
                $possibleMoves = $this->eliminateMove($possibleMoves, $move);
            }

        }
        error_log("[AvoidTraps] Remaining moves: " . print_r($possibleMoves, true));
        return $possibleMoves;
    }

    /**
     * @return array
     */
    public function getAllHazards(): array
    {
        $allHazards = [];
        foreach ($this->board->snakes as $snake) {
            $allHazards = array_merge($allHazards, $snake->body);
        }

        return array_merge($allHazards, $this->board->hazards);
    }
}