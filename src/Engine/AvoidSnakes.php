<?php

declare(strict_types=1);

namespace DaveSnake\Engine;

class AvoidSnakes extends AvoidanceBaseClass implements AvoidanceInterface
{
    public function filterMoves($possibleMoves): array
    {
        $head = $this->me->head;
        $tail = end($this->me->body);

        // @TODO: Worry about tail if I just ate (gonna need state)
        foreach ($this->board->snakes as $snake) {
            foreach($snake->body as $part) {
                if($head->y + 1 == $part->y && $head->x == $part->x) {
                    if(!($head->y + 1 == $tail->y && $head->x == $tail->x)) { // Don't worry about the tail
                        $possibleMoves = $this->eliminateMove($possibleMoves, "up");
                    }
                }
                if($head->y - 1 == $part->y && $head->x == $part->x) {
                    if(!($head->y - 1 == $tail->y && $head->x == $tail->x)) {
                        $possibleMoves = $this->eliminateMove($possibleMoves, "down");
                    }
                }
                if($head->x + 1 == $part->x && $head->y == $part->y) {
                    if(!($head->x + 1 == $tail->x && $head->y == $tail->y)) {
                        $possibleMoves = $this->eliminateMove($possibleMoves, "right");
                    }
                }
                if($head->x - 1 == $part->x && $head->y == $part->y) {
                    if(!($head->x - 1 == $tail->x && $head->y == $tail->y)) {
                        $possibleMoves = $this->eliminateMove($possibleMoves, "left");
                    }
                }
            }
        }
        error_log("[AvoidSnakes] Remaining moves: " . print_r($possibleMoves, true));

        return $possibleMoves;
    }
}