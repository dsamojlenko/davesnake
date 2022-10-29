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

    private AvoidWalls $avoidWalls;
    private AvoidSnakes $avoidSnakes;
    private RandomMove $randomMove;
    private FindFood $findFood;

    public function __construct($data)
    {
        $this->data = $data;
        $this->board = new Board($data->board);
        $this->me = new BattleSnake($data->you);
        $this->me->tail = end($this->me->body);
        $this->possibleMoves = ['up', 'down', 'left', 'right'];

        $this->randomMove = new RandomMove();
        $this->avoidWalls = new AvoidWalls();
        $this->avoidSnakes = new AvoidSnakes();
        $this->findFood = new FindFood($data);
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
        $this->possibleMoves = $this->avoidWalls->getMoves($this->possibleMoves, $this->board, $this->me);
        $this->possibleMoves = $this->avoidSnakes->getMoves($this->possibleMoves, $this->board, $this->me);

        $move = false;

        foreach ($this->possibleMoves as $checkMove) {
            if ($this->findFood->findFood($checkMove, 1)) {
                $move = $checkMove;
                break;
            }
        }

        if (!$move) {
            foreach ($this->possibleMoves as $checkMove) {
                if ($this->findFood->findFood($checkMove, 2)) {
                    $move = $checkMove;
                    break;
                }
            }
        }

        if (!$move) {
            $move = $this->randomMove->getMove($this->possibleMoves);
        }
      
        error_log("Moving: " . ($move ? $move : "Oh no, nowhere to go!"));

        return $move ? $move : "up";
    }
}