<?php

declare(strict_types=1);

namespace DaveSnake\Engine;

class Engine
{
    private array $possibleMoves;

    private AvoidWalls $avoidWalls;
    private AvoidSnakes $avoidSnakes;
    private RandomMove $randomMove;
    private NearbyFood $nearbyFood;
    private AvoidHazards $avoidHazards;
    private AvoidSnakeHeads $avoidSnakeHeads;

    public function __construct($data)
    {
        $this->possibleMoves = ['up', 'down', 'left', 'right'];

        $this->randomMove = new RandomMove();
        $this->avoidWalls = new AvoidWalls($data);
        $this->avoidSnakes = new AvoidSnakes($data);
        $this->nearbyFood = new NearbyFood($data);
        $this->avoidHazards = new AvoidHazards($data);
        $this->avoidSnakeHeads = new AvoidSnakeHeads($data);
    }

    public function getMove()
    {
        // Avoid walls, snakes, and hazards
        $this->possibleMoves = $this->avoidWalls->filterMoves($this->possibleMoves);
        $this->possibleMoves = $this->avoidHazards->filterMoves($this->possibleMoves);
        $this->possibleMoves = $this->avoidSnakes->filterMoves($this->possibleMoves);
        $this->possibleMoves = $this->avoidSnakeHeads->filterMoves($this->possibleMoves);

        // Look ahead a bit and check for traps?

        // When all else fails, follow tail

        // Head towards food within the radius
        $move = $this->nearbyFood->findFood($this->possibleMoves, (int)getenv("DEFAULT_SEARCH_RADIUS"));

        // finally, random if there's anything left
        if (!$move && count($this->possibleMoves)) {
            $move = $this->randomMove->getMove($this->possibleMoves);
        }
      
        error_log("Moving: " . ($move ? $move : "Oh no, nowhere to go!"));

        return $move ? $move : "up";
    }
}