<?php

declare(strict_types=1);

namespace DaveSnake\Engine;

use DaveSnake\Models\Board;

class AvoidWalls
{
  public $board;
  public $possibleMoves;
  
  public function __construct(array $possibleMoves, Board $board)
  {
    $this->board = $board;
    $this->possibleMoves = $possibleMoves;
  }

  public function getMoves()
  {
      // Check for walls
      foreach($this->possibleMoves as $move) {
          // Check up
          if ($this->board->me->head->y + 1 > $this->board->height-1) {
              $this->possibleMoves = array_filter($this->possibleMoves, function($move) {
                  return $move !== "up";
              });
          }
          // Check down
          if ($this->board->me->head->y -1 < 0) {
              $this->possibleMoves = array_filter($this->possibleMoves, function($move) {
                  return $move !== "down";
              });
          }
          // Check left
          if ($this->board->me->head->x -1 < 0) {
              $this->possibleMoves = array_filter($this->possibleMoves, function($move) {
                  return $move !== "left";
              });
          }
          // Check right
          if ($this->board->me->head->x + 1 > $this->board->width-1) {
              $this->possibleMoves = array_filter($this->possibleMoves, function($move) {
                  return $move !== "right";
              });
          }
      }

      return $this->possibleMoves;
  }
}