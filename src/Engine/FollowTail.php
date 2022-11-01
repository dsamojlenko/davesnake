<?php


namespace DaveSnake\Engine;


use DaveSnake\Engine\Concerns\InteractsWithBoard;
use DaveSnake\Models\BattleSnake;
use DaveSnake\Models\Board;

class FollowTail
{
    private Board $board;
    private BattleSnake $me;

    use InteractsWithBoard;

    public function __construct($data)
    {
        $this->board = new Board($data->board);
        $this->me = new BattleSnake($data->you);
    }

    public function getMove($possibleMoves)
    {
        // where's my tail?
        $tail = end($this->me->body);
        $head = $this->me->head;

        error_log("[FollowTail] food: " . print_r($this->board->food, true));
        error_log("[FollowTail] head: " . print_r($this->me->head, true));

        if(count($this->me->body) < 8) {
            return false;
        }

        if($this->me->health < 50) {
            return false;
        }

        if($this->me->health === 100) {
            return false;
        }

        $adjacentCells = $this->board->getAdjacentCells($head);

        foreach($adjacentCells as $move => $cell) {
            if($tail->x === $cell->x && $tail->y === $cell->y) {
                if(in_array($move, $possibleMoves)) {
                    return $move;
                }
            }
        }
        return false;
    }
}