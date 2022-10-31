<?php

declare(strict_types=1);

namespace DaveSnake\Engine;

use DaveSnake\Models\BattleSnake;
use DaveSnake\Models\Board;
use DaveSnake\Models\Coordinates;

class Engine
{
    protected array $possibleMoves;
    public BattleSnake $me;
    protected Board $board;

    protected AvoidWalls $avoidWalls;
    protected AvoidSnakes $avoidSnakes;
    protected RandomMove $randomMove;
    protected FoodFinder $foodFinder;
    protected AvoidHazards $avoidHazards;
    protected AvoidSnakeHeads $avoidSnakeHeads;
    protected AvoidTraps $avoidTraps;

    public function __construct($data)
    {
        $this->me = new BattleSnake($data->you);
        $this->board = new Board($data->board);

        $this->randomMove = new RandomMove();
        $this->avoidWalls = new AvoidWalls($data);
        $this->avoidSnakes = new AvoidSnakes($data);
        $this->foodFinder = new FoodFinder($data);
        $this->avoidHazards = new AvoidHazards($data);
        $this->avoidSnakeHeads = new AvoidSnakeHeads($data);
        $this->avoidTraps = new AvoidTraps($data);
    }

    public function getPossibleMoves(): array
    {
        $possibleMoves = ['up', 'down', 'left', 'right'];

        // Avoid walls, snakes, and hazards
        $possibleMoves = $this->avoidWalls->filterMoves($possibleMoves);
        $possibleMoves = $this->avoidHazards->filterMoves($possibleMoves);
        $possibleMoves = $this->avoidSnakes->filterMoves($possibleMoves);

        // Avoid snake heads I can't beat
        return $this->avoidSnakeHeads->filterMoves($possibleMoves);
    }

    public function getMove()
    {
        $this->possibleMoves = $this->getPossibleMoves($this->me->head);

        // Look ahead a bit and check for dead ends
        $this->possibleMoves = $this->avoidTraps->filterMoves($this->possibleMoves);

        $move = false;

        // Grab food in any adjacent cells
        $move = $this->foodFinder->findAdjacentFood($this->possibleMoves);

        if (!$move) {
            $move = $this->foodFinder->findFoodInRadius($this->possibleMoves, 0);
        }

        // finally, random if there's anything left
        if (!$move && count($this->possibleMoves)) {
            $move = $this->randomMove->getMove($this->possibleMoves);
        }
      
        error_log("Moving: " . ($move ? $move : "Oh no, nowhere to go!"));

        return $move ? $move : "up";
    }
}