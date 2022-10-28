<?php

declare(strict_types=1);

namespace DaveSnake\Engine;

use DaveSnake\Models\Board;
use DaveSnake\Models\BattleSnake;

class AvoidWalls
{
  public $board;
  public $possibleMoves;
  public $me;
  
  public function __construct(array $possibleMoves, Board $board, BattleSnake $me)
  {
    $this->board = $board;
    $this->possibleMoves = $possibleMoves;
    $this->me = $me;
  }

  public function getMoves()
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
      
      return $this->possibleMoves;
  }
}