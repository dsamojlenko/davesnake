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

    public function __construct($data)
    {
        $this->possibleMoves = ['up', 'down', 'left', 'right'];

        $this->randomMove = new RandomMove();
        $this->avoidWalls = new AvoidWalls($data);
        $this->avoidSnakes = new AvoidSnakes($data);
        $this->nearbyFood = new NearbyFood($data);
    }

    public function getMove()
    {
        // Eliminate walls and snakes
        $this->possibleMoves = $this->avoidWalls->getMoves($this->possibleMoves);
        $this->possibleMoves = $this->avoidSnakes->getMoves($this->possibleMoves);

        // If there's food within one or two spaces, grab it
        $move = $this->nearbyFood->findFood($this->possibleMoves, 4);

        // finally, random
        if (!$move) {
            $move = $this->randomMove->getMove($this->possibleMoves);
        }
      
        error_log("Moving: " . ($move ? $move : "Oh no, nowhere to go!"));

        return $move ? $move : "up";
    }
}