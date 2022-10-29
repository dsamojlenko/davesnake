<?php

declare(strict_types=1);

namespace DaveSnake\Engine;

use DaveSnake\Models\BattleSnake;
use DaveSnake\Models\Board;

class Engine
{
    private $data;
    private BattleSnake $me;
    private Board $board;
    private array $possibleMoves;

    private AvoidWalls $avoidWalls;
    private AvoidSnakes $avoidSnakes;
    private RandomMove $randomMove;
    private NearbyFood $nearbyFood;

    public function __construct($data)
    {
        $this->data = $data;
        $this->board = new Board($data->board);
        $this->me = new BattleSnake($data->you);
        $this->me->tail = end($this->me->body);
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

        // Ok let's find the closest food and move towards it
        if (!$move) {

        }

        // finally, random
        if (!$move) {
            $move = $this->randomMove->getMove($this->possibleMoves);
        }
      
        error_log("Moving: " . ($move ? $move : "Oh no, nowhere to go!"));

        return $move ? $move : "up";
    }
}