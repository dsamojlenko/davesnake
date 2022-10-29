<?php

declare(strict_types=1);

namespace DaveSnake\Engine;

use DaveSnake\Models\Move;

class AvoidWalls extends AvoidanceBaseClass implements AvoidanceInterface
{
    public function filterMoves($possibleMoves): array
    {
        $head = $this->me->head;

        // Check for walls
        foreach ($possibleMoves as $move) {
            // Check up
            if ($head->y + 1 > $this->board->height - 1) {
                $possibleMoves = $this->eliminateMove($possibleMoves, Move::$UP);
            }
            // Check down
            if ($head->y - 1 < 0) {
                $possibleMoves = $this->eliminateMove($possibleMoves, Move::$DOWN);
            }
            // Check left
            if ($head->x - 1 < 0) {
                $possibleMoves = $this->eliminateMove($possibleMoves, Move::$LEFT);
            }
            // Check right
            if ($head->x + 1 > $this->board->width - 1) {
                $possibleMoves = $this->eliminateMove($possibleMoves, Move::$RIGHT);
            }
        }

        return $possibleMoves;
    }
}