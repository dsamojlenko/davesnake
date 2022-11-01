<?php


namespace DaveSnake\Engine;


use DaveSnake\Concerns\InteractsWithBoard;
use DaveSnake\Models\BattleSnake;
use DaveSnake\Models\Board;

class AvoidanceBaseClass
{
    protected Board $board;
    protected BattleSnake $me;

    use InteractsWithBoard;

    public function __construct($data)
    {
        $this->board = new Board($data->board);
        $this->me = new BattleSnake($data->you);
    }

    protected function eliminateMove($possibleMoves, $eliminated): array
    {
        return array_filter($possibleMoves, function ($move) use ($eliminated) {
            return $move !== $eliminated;
        });
    }
}