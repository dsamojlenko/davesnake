<?php

declare(strict_types=1);

namespace DaveSnake\Engine;

use DaveSnake\Models\BattleSnake;
use DaveSnake\Models\Board;

class Engine
{
    protected array $possibleMoves;
    protected BattleSnake $me;
    protected Board $board;

    protected AvoidWalls $avoidWalls;
    protected AvoidSnakes $avoidSnakes;
    protected RandomMove $randomMove;
    protected NearbyFood $nearbyFood;
    protected AvoidHazards $avoidHazards;
    protected AvoidSnakeHeads $avoidSnakeHeads;

    public function __construct($data)
    {
        $this->possibleMoves = ['up', 'down', 'left', 'right'];
        $this->me = new BattleSnake($data->you);
        $this->board = new Board($data->board);

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
        $move = false;

        // When health is good, look for food only in immediate vicinity
        if ($this->me->health > 75) {
            $move = $this->nearbyFood->findFood($this->possibleMoves, (int) ($this->board->width / 2));
        }

        // Getting low, better search all over
        if ($this->me->health < 50) {
            $move = $this->nearbyFood->findFood($this->possibleMoves, 0);
        }

        // finally, random if there's anything left
        if (!$move && count($this->possibleMoves)) {
            $move = $this->randomMove->getMove($this->possibleMoves);
        }
      
        error_log("Moving: " . ($move ? $move : "Oh no, nowhere to go!"));

        return $move ? $move : "up";
    }
}