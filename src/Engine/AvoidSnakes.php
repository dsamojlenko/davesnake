<?php

declare(strict_types=1);

namespace DaveSnake\Engine;

class AvoidSnakes extends AvoidanceBaseClass implements AvoidanceInterface
{
    public function filterMoves($possibleMoves): array
    {
        $head = $this->me->head;
        $tail = end($this->me->body);

        foreach ($this->board->snakes as $snake) {
            foreach($snake->body as $part) {
                if($head->y + 1 == $part->y && $head->x == $part->x) {
                    if(!($head->y + 1 == $tail->y && $head->x == $tail->x)) { // Don't worry about the tail
                        $possibleMoves = array_filter($possibleMoves, function($move) {
                            return $move !== "up"; // can't go up
                        });
                    }
                }
                if($head->y - 1 == $part->y && $head->x == $part->x) {
                    if(!($head->y - 1 == $tail->y && $head->x == $tail->x)) {
                        $possibleMoves = array_filter($possibleMoves, function($move) {
                            return $move !== "down"; // can't go down
                        });
                    }
                }
                if($head->x + 1 == $part->x && $head->y == $part->y) {
                    if(!($head->x + 1 == $tail->x && $head->y == $tail->y)) {
                        $possibleMoves = array_filter($possibleMoves, function($move) {
                            return $move !== "right"; // can't go right
                        });
                    }
                }
                if($head->x - 1 == $part->x && $head->y == $part->y) {
                    if(!($head->x - 1 == $tail->x && $head->y == $tail->y)) {
                        $possibleMoves = array_filter($possibleMoves, function($move) {
                            return $move !== "left"; // can't go left
                        });
                    }
                }
            }
        }
        return $possibleMoves;
    }
}