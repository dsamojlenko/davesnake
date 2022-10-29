<?php


namespace DaveSnake\Engine;


use DaveSnake\Models\BattleSnake;
use DaveSnake\Models\Coordinates;

class AvoidSnakeHeads extends AvoidanceBaseClass implements AvoidanceInterface
{
    public function filterMoves($possibleMoves): array
    {
        error_log("[AvoidSnakeHeads] Looking for nearby snake heads");
        foreach ($this->board->snakes as $snake) {
            $snake = new BattleSnake($snake);

            // skip me
            if ($snake->id !== $this->me->id) {
                // only worried if I can't beat the snake
                if ($snake->length >= $this->me->length) {
                    // only the ones close by
                    if ($this->getDistanceToTarget($snake->head) <= 2) {
                        $head = new Coordinates($snake->body[0]);
                        foreach ($possibleMoves as $move) {
                            $target = $this->getMoveTarget($move, 1);
                            $adjacents = $this->getAdjacentCells($target);
                            if (in_array($head->getLocationId($this->board), $adjacents)) {
                                error_log("[AvoidSnakeHeads] Snake nearby " . print_r($head, true));
                                $possibleMoves = $this->eliminateMove($possibleMoves, $move);
                            }
                        }
                    }
                }
            }
        }

        return $possibleMoves;
    }
}