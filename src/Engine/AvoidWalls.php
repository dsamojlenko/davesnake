<?php

declare(strict_types=1);

namespace DaveSnake\Engine;

use DaveSnake\Models\Board;
use DaveSnake\Models\BattleSnake;
use DaveSnake\Models\MoveTypes;

class AvoidWalls
{
    private Board $board;
    private BattleSnake $me;

    public function __construct($data)
    {
        $this->board = new Board($data->board);
        $this->me = new BattleSnake($data->you);
    }

    private function eliminateMove($possibleMoves, $eliminated): array
    {
        return array_filter($possibleMoves, function($move) use ($eliminated) {
            return $move !== $eliminated;
        });
    }

  public function getMoves($possibleMoves)
  {
      // Check for walls
      foreach($possibleMoves as $move) {
          // Check up
          if ($this->me->head->y + 1 > $this->board->height-1) {
              $possibleMoves = $this->eliminateMove($possibleMoves, MoveTypes::$UP);
          }
          // Check down
          if ($this->me->head->y -1 < 0) {
              $possibleMoves = $this->eliminateMove($possibleMoves, MoveTypes::$DOWN);
          }
          // Check left
          if ($this->me->head->x -1 < 0) {
              $possibleMoves = $this->eliminateMove($possibleMoves, MoveTypes::$LEFT);
          }
          // Check right
          if ($this->me->head->x + 1 > $this->board->width-1) {
              $possibleMoves = $this->eliminateMove($possibleMoves, MoveTypes::$RIGHT);
          }
      }
      
      return $possibleMoves;
  }
}