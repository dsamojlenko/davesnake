<?php


namespace DaveSnake\Engine\Concerns;


use DaveSnake\Models\Coordinates;
use DaveSnake\Models\Move;

trait IdentifiesLocations
{
    // private BattleSnake $me;

    public function getTarget(string $move, int $distance): Coordinates
    {
        $x = 0;
        $y = 0;

        switch($move) {
            case Move::$UP:
                $x = $this->me->head->x;
                $y = $this->me->head->y + $distance;
                break;
            case Move::$DOWN:
                $x = $this->me->head->x;
                $y = $this->me->head->y - $distance;
                break;
            case Move::$LEFT:
                $x = $this->me->head->x - $distance;
                $y = $this->me->head->y;
                break;
            case Move::$RIGHT:
                $x = $this->me->head->x + $distance;
                $y = $this->me->head->y;
                break;
        }

        return new Coordinates((object) [ "x" => $x, "y" => $y]);
    }

    /**
     * @param Coordinates $closest
     * @return array
     */
    public function getTargetDirections(Coordinates $closest): array
    {
        $targetDirections = [];

        if ($closest->x < $this->me->head->x) {
            array_push($targetDirections, Move::$LEFT);
        }

        if ($closest->x > $this->me->head->x) {
            array_push($targetDirections, Move::$RIGHT);
        }

        if ($closest->y < $this->me->head->y) {
            array_push($targetDirections, Move::$DOWN);
        }

        if ($closest->y > $this->me->head->y) {
            array_push($targetDirections, Move::$UP);
        }

        return $targetDirections;
    }
}