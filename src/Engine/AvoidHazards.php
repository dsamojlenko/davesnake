<?php


namespace DaveSnake\Engine;


class AvoidHazards extends AvoidanceBaseClass implements AvoidanceInterface
{
    public function filterMoves($possibleMoves): array
    {
        error_log("[AvoidHazards] Check for hazards");
        $remainingMoves = $possibleMoves;
        foreach($possibleMoves as $move) {
            $target = $this->getMoveTargetCoordinates($move, 1);
            // error_log("[AvoidHazards] target: " . print_r($target, true));
            foreach($this->board->hazards as $hazard) {
                if($hazard->x === $target->x && $hazard->y === $target->y) {
                    // error_log("[AvoidHazards] Eliminating move: " . $move);
                    $remainingMoves = $this->eliminateMove($remainingMoves, $move);
                }
            }
        }

        error_log("[AvoidHazards] Remaining moves: " . print_r($remainingMoves, true));

        return $remainingMoves;
    }
}