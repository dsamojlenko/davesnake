<?php


namespace DaveSnake\Engine2;


use DaveSnake\Concerns\InteractsWithBoard;
use DaveSnake\Models\BattleSnake;
use DaveSnake\Models\Board;
use DaveSnake\Models\Coordinates;

class Engine
{
    public BattleSnake $me;
    public Board $board;
    public Helpers $helpers;

    use InteractsWithBoard;

    public function __construct($data)
    {
        $this->me = new BattleSnake($data->you);
        $this->board = new Board($data->board);
        $this->helpers = new Helpers($data);
    }

    public function avoidWalls($possibleMoves): array
    {
        return array_filter($possibleMoves, function($move) {
            $target = $this->getMoveTargetCoordinates($move, 1);

            if ($target->x < 0 || $target->x > $this->board->width -1 || $target->y < 0 || $target->y > $this->board->height -1) {
                return false;
            }
            return true;
        });
    }

    public function avoidSnakes($possibleMoves): array
    {
        $snakeParts = [];
        foreach($this->board->snakes as $snake) {
            $snakeParts = [...$snakeParts, ...$snake->body];
        }

        return array_filter($possibleMoves, function($move) use ($snakeParts) {
            return !$this->helpers->findTargetInArray($this->getMoveTargetCoordinates($move, 1), $snakeParts);
        });
    }

    public function findAdjacentFood($possibleMoves)
    {
        $food = $this->board->food;
        foreach($possibleMoves as $move)
        {
            if ($this->helpers->findTargetInArray($this->getMoveTargetCoordinates($move, 1), $food)) {
                return $move;
            }
        }

        return false;
    }

    public function followTail($possibleMoves)
    {
        if(count($this->me->body) < 5) {
            return false;
        }

        if($this->me->health < 50) {
            return false;
        }

        $tail = new Coordinates(end($this->me->body));

        if($this->getDistanceToTarget($tail) < 4) {
            $directions = array_intersect($possibleMoves, $this->getDirectionsToTarget($tail));
            if($directions) {
                return end($directions);
            }
        }

        return false;
    }

    public function getMove()
    {
        $possibleMoves = ['up', 'down', 'left', 'right'];
        $possibleMoves = $this->avoidWalls($possibleMoves);
        $possibleMoves = $this->avoidSnakes($possibleMoves);

        if(!$possibleMoves) {
            error_log("No moves left...");
            return "left";
        }

        $move = $this->findAdjacentFood($possibleMoves);

        if(!$move) {
            $move = $this->followTail($possibleMoves);
        }

        if(!$move) {
            $move = $possibleMoves[array_rand($possibleMoves)];
        }

        error_log("Moving " . $move);
        return $move;
    }
}