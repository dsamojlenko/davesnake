<?php

declare(strict_types=1);

namespace DaveSnake;

class Engine
{
    private $data;
    private $me;
    private $board;
    private array $possibleMoves;

    public function __construct($data)
    {
        $this->data = $data;
        $this->board = $data->board;
        $this->me = $data->you;
        $this->me->tail = end($this->me->body);
        $this->possibleMoves = ['up', 'down', 'left', 'right'];
    }

    private function avoidWalls()
    {
        // Check for walls
        foreach($this->possibleMoves as $move) {
            // Check up
            if ($this->me->head->y + 1 > $this->board->height-1) {
                $this->possibleMoves = array_filter($this->possibleMoves, function($move) {
                    return $move !== "up";
                });
            }
            // Check down
            if ($this->me->head->y -1 < 0) {
                $this->possibleMoves = array_filter($this->possibleMoves, function($move) {
                    return $move !== "down";
                });
            }
            // Check left
            if ($this->me->head->x -1 < 0) {
                $this->possibleMoves = array_filter($this->possibleMoves, function($move) {
                    return $move !== "left";
                });
            }
            // Check right
            if ($this->me->head->x + 1 > $this->board->width-1) {
                $this->possibleMoves = array_filter($this->possibleMoves, function($move) {
                    return $move !== "right";
                });
            }
        }
    }

    private function avoidSnakes()
    {
        // Avoid snakes
        foreach ($this->board->snakes as $snake) {
            foreach($snake->body as $part) {
                if($this->me->head->y + 1 == $part->y && $this->me->head->x == $part->x) {
                    if(!($this->me->head->y + 1 == $this->me->tail->y && $this->me->head->x == $this->me->tail->x)) {
                        $this->possibleMoves = array_filter($this->possibleMoves, function($move) {
                            return $move !== "up";
                        });
                    }
                }
                if($this->me->head->y - 1 == $part->y && $this->me->head->x == $part->x) {
                    if(!($this->me->head->y - 1 == $this->me->tail->y && $this->me->head->x == $this->me->tail->x)) {
                        $this->possibleMoves = array_filter($this->possibleMoves, function($move) {
                            return $move !== "down";
                        });
                    }
                }
                if($this->me->head->x + 1 == $part->x && $this->me->head->y == $part->y) {
                    if(!($this->me->head->x + 1 == $this->me->tail->x && $this->me->head->y == $this->me->tail->y)) {
                        $this->possibleMoves = array_filter($this->possibleMoves, function($move) {
                            return $move !== "right";
                        });
                    }
                }
                if($this->me->head->x - 1 == $part->x && $this->me->head->y == $part->y) {
                    if(!($this->me->head->x - 1 == $this->me->tail->x && $this->me->head->y == $this->me->tail->y)) {
                        $this->possibleMoves = array_filter($this->possibleMoves, function($move) {
                            return $move !== "left";
                        });
                    }
                }
            }
        }
    }

    private function lookForFood()
    {
        $spaces = 1;

        // Look for nearby food
        foreach ($this->board->food as $food) {
            // Check up
            if (in_array('up', $this->possibleMoves)) {
                if ($this->me->head->y + $spaces == $food->y && $this->me->head->x == $food->x) {
                    return 'up';
                }
            }
            // Check down
            if (in_array('down', $this->possibleMoves)) {
                if ($this->me->head->y - $spaces == $food->y && $this->me->head->x == $food->x) {
                    return 'down';
                }
            }
            // Check right
            if (in_array('right', $this->possibleMoves)) {
                if ($this->me->head->x + $spaces == $food->x && $this->me->head->y == $food->y) {
                    return 'right';
                }
            }
            // Check left
            if (in_array('left', $this->possibleMoves)) {
                if ($this->me->head->x - $spaces == $food->x && $this->me->head->y == $food->y) {
                    return 'left';
                }
            }
            return;
        }
    }

    public function getMove()
    {
        $this->avoidWalls();
        $this->avoidSnakes();

        // Default random in case the below choices don't work out
        $move = $this->possibleMoves[array_rand($this->possibleMoves)];

        error_log("Moving: " . $move);

        return $move;
    }
}