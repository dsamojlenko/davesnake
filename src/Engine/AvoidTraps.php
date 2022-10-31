<?php


namespace DaveSnake\Engine;


use DaveSnake\Models\Coordinates;

class AvoidTraps extends AvoidanceBaseClass implements AvoidanceInterface
{

    public function filterMoves($possibleMoves): array
    {
        error_log("[AvoidTraps] Looking ahead to check for exits");
        foreach($possibleMoves as $move) {
            $target = $this->getMoveTargetCoordinates($move, 1);
            error_log("[AvoidTraps] oldMe: " . print_r($this->me->body, true));
            $newMe = $this->me;
            $newBody = $newMe->body;
//            $newMe->head = new Coordinates($newBody[0]);
            array_unshift($newBody, $target); // pop a new head on
            array_pop($newBody); // pop my tail off
            $newMe->body = $newBody;
            error_log("[AvoidTraps] newMe: " . print_r($newMe->body, true));
            $next = new Engine((object)[
                "board" => $this->board,
                "you" => $newMe,
            ]);

            $newPossibleMoves = $next->getPossibleMoves();
            error_log("[AvoidTraps] nextPossibleMoves: " . print_r($newPossibleMoves, true));
            error_log("[AvoidTraps] nextMe: " . print_r($next->me->head, true));
            if(!count($newPossibleMoves)) {
                $possibleMoves = $this->eliminateMove($possibleMoves, $move);
            }
        }

        return $possibleMoves;
    }
}