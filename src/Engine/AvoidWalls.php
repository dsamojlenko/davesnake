<?php

declare(strict_types=1);

namespace DaveSnake\Engine;

use DaveSnake\Models\Board;
use DaveSnake\Models\BattleSnake;

class AvoidWalls
{
    private Board $board;
    private BattleSnake $me;

    public function __construct($data)
    {
        $this->board = new Board($data->board);
        $this->me = new BattleSnake($data->you);
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