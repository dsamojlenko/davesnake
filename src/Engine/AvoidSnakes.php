<?php

declare(strict_types=1);

namespace DaveSnake\Engine;

use DaveSnake\Models\Coordinates;

class AvoidSnakes extends AvoidanceBaseClass implements AvoidanceInterface
{
    protected Coordinates $tail;

    public function __construct($data)
    {
        parent::__construct($data);
        $this->tail = new Coordinates(end($data->you->body));
    }

    public function filterMoves($possibleMoves): array
    {
        foreach ($this->board->snakes as $snake) {
            foreach($snake->body as $part) {
                if($this->me->head->y + 1 == $part->y && $this->me->head->x == $part->x) {
                    if(!($this->me->head->y + 1 == $this->tail->y && $this->me->head->x == $this->tail->x)) { // Don't worry about the tail
                        $possibleMoves = array_filter($possibleMoves, function($move) {
                            return $move !== "up"; // can't go up
                        });
                    }
                }
                if($this->me->head->y - 1 == $part->y && $this->me->head->x == $part->x) {
                    if(!($this->me->head->y - 1 == $this->tail->y && $this->me->head->x == $this->tail->x)) {
                        $possibleMoves = array_filter($possibleMoves, function($move) {
                            return $move !== "down"; // can't go down
                        });
                    }
                }
                if($this->me->head->x + 1 == $part->x && $this->me->head->y == $part->y) {
                    if(!($this->me->head->x + 1 == $this->tail->x && $this->me->head->y == $this->tail->y)) {
                        $possibleMoves = array_filter($possibleMoves, function($move) {
                            return $move !== "right"; // can't go right
                        });
                    }
                }
                if($this->me->head->x - 1 == $part->x && $this->me->head->y == $part->y) {
                    if(!($this->me->head->x - 1 == $this->tail->x && $this->me->head->y == $this->tail->y)) {
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