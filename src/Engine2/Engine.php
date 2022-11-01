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
        $snakeParts = $this->getSnakeBodies();

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

    public function findNearbyFood($possibleMoves, $radius = 5)
    {
        $food = array_map(function($food) {
            return (object)[
                "x" => $food->x,
                "y" => $food->y,
                "distance" => $this->getDistanceToTarget(new Coordinates($food)),
            ];
        }, $this->board->food);

        usort($food, function ($a, $b) {
            return $a->distance <=> $b->distance;
        });

        $nearest = new Coordinates($food[0]);

        if ($this->getDistanceToTarget($nearest) < $radius) {
            $directionsToTarget = array_intersect($possibleMoves, $this->getDirectionsToTarget($nearest));
            if($directionsToTarget) {
                return end($directionsToTarget);
            }
        }

        return false;
    }

    public function followTail($possibleMoves, $lengthThreshold = 5, $healthThreshold = 50)
    {
        if(count($this->me->body) < $lengthThreshold) {
            return false;
        }

        if($this->me->health < $healthThreshold) {
            return false;
        }

        $tail = new Coordinates(end($this->me->body));

        $directions = array_intersect($possibleMoves, $this->getDirectionsToTarget($tail));
        if($directions) {
            return end($directions);
        }

        return false;
    }

    public function peekAhead($possibleMoves)
    {
        error_log("[peekAhead] remaining before peek: " . print_r($possibleMoves, true));
        $snakeParts = $this->getSnakeBodies();
        $allHazards = [...$snakeParts, ...$this->board->hazards];

        return array_filter($possibleMoves, function($move) use ($allHazards) {
            $target = $this->getMoveTargetCoordinates($move, 1);

            $except = "";
            // don't check where we came from
            if(in_array($move, ["up", "down"])) {
                $except = $move === "up" ? "down" : "up";
            }
            if(in_array($move, ["left", "right"])) {
                $except = $move === "left" ? "right" : "left";
            }

            $targetAdjacentCells = $this->helpers->getAdjacentCells($target, [$except]);

            error_log("[peekAhead]: targetAdjacentCells" . print_r($targetAdjacentCells, true));

            if(isset($targetAdjacentCells[$move])) {
                error_log("[peekAhead] checking: " . print_r($targetAdjacentCells[$move], true));
                if($this->helpers->findTargetInArray($targetAdjacentCells[$move], $allHazards)) {
                    error_log("[peekAhead] found and eliminating " . $move);
                    return false;
                }
            }

            return true;
        });
    }

    public function getMove()
    {
        $possibleMoves = ['up', 'down', 'left', 'right'];
        $possibleMoves = $this->avoidWalls($possibleMoves);
        $possibleMoves = $this->avoidSnakes($possibleMoves);
        $possibleMoves = $this->peekAhead($possibleMoves);
        error_log("[peekAhead] remaining after peek: " . print_r($possibleMoves, true));
        // avoid or attack snake heads

        if(!$possibleMoves) {
            error_log("No moves left...");
            return "left";
        }

        $move = $this->findAdjacentFood($possibleMoves);

        if(!$move) {
            $move = $this->followTail($possibleMoves, 10, 25);
        }

        if(!$move) {
            $move = $this->findNearbyFood($possibleMoves, $this->board->width / 2);
        }

        if(!$move) {
            $move = $possibleMoves[array_rand($possibleMoves)];
        }

        error_log("Moving " . $move);
        return $move;
    }

    /**
     * @return array
     */
    protected function getSnakeBodies(): array
    {
        $snakeParts = [];
        foreach ($this->board->snakes as $snake) {
            $snakeParts = [...$snakeParts, ...$snake->body];
        }

        return $snakeParts;
    }
}