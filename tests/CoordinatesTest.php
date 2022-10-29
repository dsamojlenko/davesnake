<?php

test('example', function () {
    $board = new \DaveSnake\Models\Board((object) [
        "width" => 11,
        "height" => 11,
        "food" => [],
        "hazards" => [],
        "snakes" => []
    ]);

    // Top left corner
    $coordinates = new \DaveSnake\Models\Coordinates((object) [
        "x" => 0,
        "y" => 0,
    ]);

    expect($coordinates->getLocationId($board))->toEqual(1);

    $coordinates = new \DaveSnake\Models\Coordinates((object) [
        "x" => 10,
        "y" => 10,
    ]);

    // Top right corner
    expect($coordinates->getLocationId($board))->toEqual(121);
});
