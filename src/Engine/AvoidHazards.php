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
        $moveTargets = [];
        $hazards = [];

        foreach($this->board->hazards as $hazard) {
            array_push($hazards, new Coordinates($hazard));
        }

        foreach($possibleMoves as $move) {
            $target = $this->getMoveTarget($move, 1);
            foreach($hazards as $hazard) {
                if($hazard->x === $target->x && $hazard->y === $target->y) {
                    $this->eliminateMove($possibleMoves, $move);
                }
            }
        }

        return $possibleMoves;
    }
}