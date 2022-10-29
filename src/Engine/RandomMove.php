<?php


namespace DaveSnake\Engine;


class RandomMove
{
    public function getMove(array $possibleMoves)
    {
        return $possibleMoves[array_rand($possibleMoves)];
    }
}