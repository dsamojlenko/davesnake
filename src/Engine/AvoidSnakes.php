<?php

declare(strict_types=1);

namespace DaveSnake\Engine;

use DaveSnake\Models\BattleSnake;
use DaveSnake\Models\Board;
use DaveSnake\Models\Coordinates;

class AvoidSnakes
{
    private Board $board;
    private BattleSnake $me;
    private Coordinates $tail;

    public function __construct($data)
    {
        $this->board = new Board($data->board);
        $this->me = new BattleSnake($data->you);
        $this->tail = new Coordinates(end($data->you->body));
    }

    public function getMoves($possibleMoves)
    {
        // @TODO: don't worry about snake bums
        foreach ($this->board->snakes as $snake) {
            foreach($snake->body as $part) {
                if($this->me->head->y + 1 == $part->y && $this->me->head->x == $part->x) {
                    if(!($this->me->head->y + 1 == $this->tail->y && $this->me->head->x == $this->tail->x)) {
                        $possibleMoves = array_filter($possibleMoves, function($move) {
                            return $move !== "up";
                        });
                    }
                }
                if($this->me->head->y - 1 == $part->y && $this->me->head->x == $part->x) {
                    if(!($this->me->head->y - 1 == $this->tail->y && $this->me->head->x == $this->tail->x)) {
                        $possibleMoves = array_filter($possibleMoves, function($move) {
                            return $move !== "down";
                        });
                    }
                }
                if($this->me->head->x + 1 == $part->x && $this->me->head->y == $part->y) {
                    if(!($this->me->head->x + 1 == $this->tail->x && $this->me->head->y == $this->tail->y)) {
                        $possibleMoves = array_filter($possibleMoves, function($move) {
                            return $move !== "right";
                        });
                    }
                }
                if($this->me->head->x - 1 == $part->x && $this->me->head->y == $part->y) {
                    if(!($this->me->head->x - 1 == $this->tail->x && $this->me->head->y == $this->tail->y)) {
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