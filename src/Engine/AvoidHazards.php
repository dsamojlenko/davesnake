<?php


namespace DaveSnake\Engine;


use DaveSnake\Engine\Concerns\IdentifiesLocations;
use DaveSnake\Models\BattleSnake;
use DaveSnake\Models\Board;

class AvoidHazards
{
    private Board $board;
    private BattleSnake $me;

    use IdentifiesLocations;

    public function __construct($data)
    {
        $this->board = new Board($data->board);
        $this->me = new BattleSnake($data->you);
    }

    private function eliminateMove($possibleMoves, $eliminated): array
    {
        return array_filter($possibleMoves, function ($move) use ($eliminated) {
            return $move !== $eliminated;
        });
    }

    public function filterMoves($possibleMoves)
    {
        error_log("[AvoidHazards] Check for hazards: " . print_r($this->board->hazards, true));
        $remainingMoves = $possibleMoves;
        foreach($possibleMoves as $move) {
            $target = $this->getMoveTarget($move, 1);
            error_log("[AvoidHazards] target: " . print_r($target, true));
            foreach($this->board->hazards as $hazard) {
                if($hazard->x === $target->x && $hazard->y === $target->y) {
                    error_log("[AvoidHazards] Eliminating move: " . $move);
                    $remainingMoves = $this->eliminateMove($remainingMoves, $move);
                }
            }
        }

        error_log("[AvoidHazards] Remaining moves: " . print_r($remainingMoves, true));

        return $remainingMoves;
    }
}