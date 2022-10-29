<?php


namespace DaveSnake\Engine;


use DaveSnake\Engine\Concerns\IdentifiesLocations;
use DaveSnake\Models\BattleSnake;
use DaveSnake\Models\Board;
use DaveSnake\Models\Coordinates;

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

    public function getMoves($possibleMoves)
    {
        error_log("[AvoidHazards] Check for hazards");
        $hazards = [];

        foreach($this->board->hazards as $hazard) {
            array_push($hazards, new Coordinates($hazard));
        }

        error_log("[AvoidHazards] Hazards: " . print_r($hazards, true));

        foreach($possibleMoves as $move) {
            $target = $this->getMoveTarget($move, 1);
            foreach($hazards as $hazard) {
                if($hazard->x === $target->x && $hazard->y === $target->y) {
                    $this->eliminateMove($possibleMoves, $move);
                }
            }
        }

        error_log("[AvoidHazards] Remaining moves: " . print_r($possibleMoves, true));

        return $possibleMoves;
    }
}