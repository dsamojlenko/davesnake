<?php

declare(strict_types=1);

namespace DaveSnake\Engine;

use DaveSnake\Models\BattleSnake;
use DaveSnake\Models\Board;

class AvoidSnakes
{
    public $board;
    public $possibleMoves;
    public $me;

    public function __construct(array $possibleMoves, Board $board, BattleSnake $me)
    {
        $this->board = $board;
        $possibleMoves = $possibleMoves;
        $this->me = $me;
    }

    public function getMoves($possibleMoves)
    {
        // Avoid snakes
        foreach ($this->board->snakes as $snake) {
            foreach($snake->body as $part) {
                if($this->me->head->y + 1 == $part->y && $this->me->head->x == $part->x) {
                    if(!($this->me->head->y + 1 == $this->me->tail->y && $this->me->head->x == $this->me->tail->x)) {
                        $possibleMoves = array_filter($possibleMoves, function($move) {
                            return $move !== "up";
                        });
                    }
                }
                if($this->me->head->y - 1 == $part->y && $this->me->head->x == $part->x) {
                    if(!($this->me->head->y - 1 == $this->me->tail->y && $this->me->head->x == $this->me->tail->x)) {
                        $possibleMoves = array_filter($possibleMoves, function($move) {
                            return $move !== "down";
                        });
                    }
                }
                if($this->me->head->x + 1 == $part->x && $this->me->head->y == $part->y) {
                    if(!($this->me->head->x + 1 == $this->me->tail->x && $this->me->head->y == $this->me->tail->y)) {
                        $possibleMoves = array_filter($possibleMoves, function($move) {
                            return $move !== "right";
                        });
                    }
                }
                if($this->me->head->x - 1 == $part->x && $this->me->head->y == $part->y) {
                    if(!($this->me->head->x - 1 == $this->me->tail->x && $this->me->head->y == $this->me->tail->y)) {
                        $possibleMoves = array_filter($possibleMoves, function($move) {
                            return $move !== "left";
                        });
                    }
                }
            }
        }
        return $possibleMoves;
    }
}