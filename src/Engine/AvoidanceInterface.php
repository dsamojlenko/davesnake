<?php


namespace DaveSnake\Engine;


interface AvoidanceInterface
{
    public function filterMoves($possibleMoves): array;
}