<?php

declare(strict_types=1);

namespace DaveSnake\Engine;

use DaveSnake\Models\Board;
use DaveSnake\Models\BattleSnake;

class AvoidWalls
{
  public $board;
  public $me;
  
  public function __construct(Board $board, BattleSnake $me)
  {
    $this->board = $board;
    $this->me = $me;
  }

  public function getMoves($possibleMoves)
  {
      // Check for walls
      foreach($possibleMoves as $move) {
          // Check up
          if ($this->me->head->y + 1 > $this->board->height-1) {
              $possibleMoves = array_filter($possibleMoves, function($move) {
                  return $move !== "up";
              });
          }
          // Check down
          if ($this->me->head->y -1 < 0) {
              $possibleMoves = array_filter($possibleMoves, function($move) {
                  return $move !== "down";
              });
          }
          // Check left
          if ($this->me->head->x -1 < 0) {
              $possibleMoves = array_filter($possibleMoves, function($move) {
                  return $move !== "left";
              });
          }
          // Check right
          if ($this->me->head->x + 1 > $this->board->width-1) {
              $possibleMoves = array_filter($possibleMoves, function($move) {
                  return $move !== "right";
              });
          }
      }
      
      return $possibleMoves;
  }
}