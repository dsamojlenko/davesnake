<?php

declare(strict_types=1);

namespace DaveSnake\Engine;

use DaveSnake\Engine\Concerns\InteractsWithBoard;
use DaveSnake\Models\BattleSnake;
use DaveSnake\Models\Board;
use DaveSnake\Models\Coordinates;

class Engine
{
    use InteractsWithBoard;

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
    protected FollowTail $followTail;

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
        $this->followTail = new FollowTail($data);
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

        if($this->me->health < 75) {
            $move = $this->foodFinder->findFoodInRadius($this->possibleMoves, 4);
        }

        // Follow tail
        if (!$move) {
            $move = $this->followTail->getMove($this->possibleMoves);
        }

        if (!$move) {
            $move = $this->foodFinder->findFoodInRadius($this->possibleMoves, 0);
        }


        if (!$move && count($this->possibleMoves)) {
            // Prefer heading towards center
            $center = new Coordinates((object)[
                "x" => (int)floor($this->board->width / 2),
                "y" => (int)floor($this->board->width / 2)
            ]);
            $directionsToCenter = $this->getDirectionsToTarget($center);

            if ($moves = array_intersect($this->possibleMoves, $directionsToCenter)) {
                $move = $this->randomMove->getMove($moves);
            }

            // finally, random if there's anything left
            if (!$move) {
                $move = $this->randomMove->getMove($this->possibleMoves);
            }
        }
      
        error_log("Moving: " . ($move ? $move : "Oh no, nowhere to go!"));

        return $move ? $move : "up";
    }
}