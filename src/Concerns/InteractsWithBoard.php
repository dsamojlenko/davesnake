<?php


namespace DaveSnake\Concerns;


use DaveSnake\Models\Coordinates;
use DaveSnake\Models\Move;

trait InteractsWithBoard
{
    /**
     * Get coordinates of destination relative to self
     *
     * @param string $move
     * @param int $distance
     * @return Coordinates
     */
    public function getMoveTargetCoordinates(string $move, int $distance): Coordinates
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
     * Returns up to two directions to target.
     * ie, ["left", "up"] ["right", "down"] ["down"]
     * @param Coordinates $closest
     * @return array
     */
    public function getDirectionsToTarget(Coordinates $closest): array
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

    /**
     * Calculates distance to target
     * @param Coordinates $target
     * @return int
     */
    public function getDistanceToTarget(Coordinates $target): int
    {
        $distanceX = $this->me->head->x - $target->x;
        $distanceY = $this->me->head->y - $target->y;

        if ($distanceX < 0) {
            $distanceX *= -1;
        }
        if ($distanceX < 0) {
            $distanceX *= -1;
        }

        return $distanceX + $distanceY;
    }

    /**
     * Gets adjacent cell coordinates relative to target
     * @param Coordinates $target
     * @return array
     */
    public function getAdjacentCells(Coordinates $target): array
    {
        $targetId = $target->getLocationId($this->board);

        $left = $targetId - 1;
        $right = $targetId + 1;
        $down = $targetId - $this->board->width;
        $up = $targetId + $this->board->width;

        return [$left, $right, $down, $up];
    }
}