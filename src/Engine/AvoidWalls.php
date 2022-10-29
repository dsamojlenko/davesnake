<?php

declare(strict_types=1);

namespace DaveSnake\Engine;

use DaveSnake\Models\Move;

class AvoidWalls extends AvoidanceBaseClass implements AvoidanceInterface
{
    public function filterMoves($possibleMoves): array
    {
        // Check for walls
        foreach ($possibleMoves as $move) {
            // Check up
            if ($this->me->head->y + 1 > $this->board->height - 1) {
                $possibleMoves = $this->eliminateMove($possibleMoves, Move::$UP);
            }
            // Check down
            if ($this->me->head->y - 1 < 0) {
                $possibleMoves = $this->eliminateMove($possibleMoves, Move::$DOWN);
            }
            // Check left
            if ($this->me->head->x - 1 < 0) {
                $possibleMoves = $this->eliminateMove($possibleMoves, Move::$LEFT);
            }
            // Check right
            if ($this->me->head->x + 1 > $this->board->width - 1) {
                $possibleMoves = $this->eliminateMove($possibleMoves, Move::$RIGHT);
            }
        }

        return $possibleMoves;
    }
}