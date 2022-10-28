<?php

declare(strict_types=1);

namespace DaveSnake\Engine;

use DaveSnake\Models\BattleSnake;
use DaveSnake\Models\Board;

class Engine
{
    private $data;
    private BattleSnake $me;
    private Board $board;
    private array $possibleMoves;

    public function __construct($data)
    {
        $this->data = $data;
        $this->board = new Board($data->board);
        $this->me = new BattleSnake($data->you);
        $this->me->tail = end($this->me->body);

        $this->possibleMoves = ['up', 'down', 'left', 'right'];
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

    private function lookForFood($spaces)
    {
        error_log("Looking for food " . $spaces);

        // Look for nearby food
        foreach ($this->board->food as $food) {
            // Check up
            if (in_array('up', $this->possibleMoves)) {
                if ($this->me->head->y + $spaces == $food->y && $this->me->head->x == $food->x) {
                    error_log("Found some food");
                    return 'up';
                }
            }
            // Check down
            if (in_array('down', $this->possibleMoves)) {
                if ($this->me->head->y - $spaces == $food->y && $this->me->head->x == $food->x) {
                    error_log("Found some food");
                    return 'down';
                }
            }
            // Check right
            if (in_array('right', $this->possibleMoves)) {
                if ($this->me->head->x + $spaces == $food->x && $this->me->head->y == $food->y) {
                    error_log("Found some food");
                    return 'right';
                }
            }
            // Check left
            if (in_array('left', $this->possibleMoves)) {
                if ($this->me->head->x - $spaces == $food->x && $this->me->head->y == $food->y) {
                    error_log("Found some food");
                    return 'left';
                }
            }
        }
      error_log("No food found");
      return false;
    }

    public function getMove()
    {
        // Avoid walls
        $avoidWalls = new AvoidWalls($this->possibleMoves, $this->board, $this->me);
        $this->possibleMoves = $avoidWalls->getMoves();
        error_log(print_r($this->possibleMoves, true));

        $this->avoidSnakes();

        $move =  $this->possibleMoves[array_rand($this->possibleMoves)];
      
        // if (!$move = $this->lookForFood(1)) {
        //   if (!$move = $this->lookForFood(2)) {
        //     $move =  $this->possibleMoves[array_rand($this->possibleMoves)];
        //   }
        // }
      
        error_log("Moving: " . ($move ? $move : "Oh no, nowhere to go!"));

        return $move ? $move : "up";
    }
}