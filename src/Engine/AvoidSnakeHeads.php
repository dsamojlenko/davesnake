<?php


namespace DaveSnake\Engine;


use DaveSnake\Models\BattleSnake;

class AvoidSnakeHeads extends AvoidanceBaseClass implements AvoidanceInterface
{
    public function filterMoves($possibleMoves): array
    {
        foreach ($this->board->snakes as $snake) {
            $snake = new BattleSnake($snake);

            // only worried if I can't beat the snake
            if ($snake->length >= $this->me->length) {
                $head = $snake->body[0];
                foreach($possibleMoves as $move) {
                    $target = $this->getMoveTarget($move, 2);
                    if ($head->x === $target->x && $head->y === $target->y) {
                        error_log("[AvoidSnakeHeads] Snake head nearby " . print_r($head, true));
                        $possibleMoves = $this->eliminateMove($possibleMoves, $move);
                    }
                }
            }
        }

        return $possibleMoves;
    }
}