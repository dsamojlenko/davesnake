<?php

declare(strict_types=1);

namespace DaveSnake;

class Engine
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function getMove()
    {
        $board = $this->data->board;
        $me = $this->data->you;

        $possibleMoves = ['up', 'down', 'left', 'right'];

        // Check for walls
        foreach($possibleMoves as $move) {
            // Check up
            if ($me->head->y + 1 > $board->height-1) {
                $possibleMoves = array_filter($possibleMoves, function($move) {
                    return $move !== "up";
                });
            }
            // Check down
            if ($me->head->y -1 < 0) {
                $possibleMoves = array_filter($possibleMoves, function($move) {
                    return $move !== "down";
                });
            }
            // Check left
            if ($me->head->x -1 < 0) {
                $possibleMoves = array_filter($possibleMoves, function($move) {
                    return $move !== "left";
                });
            }
            // Check right
            if ($me->head->x + 1 > $board->width-1) {
                $possibleMoves = array_filter($possibleMoves, function($move) {
                    return $move !== "right";
                });
            }
        }

        $me->tail = end($me->body);
        // Avoid snakes
        foreach ($board->snakes as $snake) {
            foreach($snake->body as $part) {
                if($me->head->y + 1 == $part->y && $me->head->x == $part->x) {
                    if(!($me->head->y + 1 == $me->tail->y && $me->head->x == $me->tail->x)) {
                        $possibleMoves = array_filter($possibleMoves, function($move) {
                            return $move !== "up";
                        });
                    }
                }
                if($me->head->y - 1 == $part->y && $me->head->x == $part->x) {
                    if(!($me->head->y - 1 == $me->tail->y && $me->head->x == $me->tail->x)) {
                        $possibleMoves = array_filter($possibleMoves, function($move) {
                            return $move !== "down";
                        });
                    }
                }
                if($me->head->x + 1 == $part->x && $me->head->y == $part->y) {
                    if(!($me->head->x + 1 == $me->tail->x && $me->head->y == $me->tail->y)) {
                        $possibleMoves = array_filter($possibleMoves, function($move) {
                            return $move !== "right";
                        });
                    }
                }
                if($me->head->x - 1 == $part->x && $me->head->y == $part->y) {
                    if(!($me->head->x - 1 == $me->tail->x && $me->head->y == $me->tail->y)) {
                        $possibleMoves = array_filter($possibleMoves, function($move) {
                            return $move !== "left";
                        });
                    }
                }
            }
        }

        // Default random in case the below choices don't work out
        $move = $possibleMoves[array_rand($possibleMoves)];

//        function getFoodCloseBy($possibleMoves, $board, $spaces) {
//            // Look for nearby food
//            foreach ($board->food as $food) {
//                // Check up
//                if (in_array('up', $possibleMoves)) {
//                    if ($me->head->y + $spaces == $food->y && $me->head->x == $food->x) {
//                        return 'up';
//                    }
//                }
//                // Check down
//                if (in_array('down', $possibleMoves)) {
//                    if ($me->head->y - $spaces == $food->y && $me->head->x == $food->x) {
//                        return 'down';
//                    }
//                }
//                // Check right
//                if (in_array('right', $possibleMoves)) {
//                    if ($me->head->x + $spaces == $food->x && $me->head->y == $food->y) {
//                        return 'right';
//                    }
//                }
//                // Check left
//                if (in_array('left', $possibleMoves)) {
//                    if ($me->head->x - $spaces == $food->x && $me->head->y == $food->y) {
//                        return 'left';
//                    }
//                }
//                return;
//            }
//        }
//
//        if (!$move = getFoodCloseBy($possibleMoves, $board, 1)) {
//            error_log("nothing 1 space away");
//            if(!$move = getFoodCloseBy($possibleMoves, $board, 2)) {
//                error_log("nothing 2 spaces away");
//                $move = $possibleMoves[array_rand($possibleMoves)];
//            }
//        }

        error_log("Moving: " . $move);

        return $move;
    }
}