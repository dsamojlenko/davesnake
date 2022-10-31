<?php

use DaveSnake\Models\Coordinates;

test('getLocationIdFromCoordinates', function () {

    $board = new \DaveSnake\Models\Board((object) [
        "width" => 11,
        "height" => 11,
        "food" => [],
        "hazards" => [],
        "snakes" => []
    ]);

    // Top left corner
    $coordinates = new Coordinates((object) [
        "x" => 0,
        "y" => 0,
    ]);

    expect($board->getLocationIdFromCoordinates($coordinates))->toEqual(1);

    $coordinates = new Coordinates((object) [
        "x" => 10,
        "y" => 10,
    ]);

    // Top right corner
    expect($board->getLocationIdFromCoordinates($coordinates))->toEqual(121);
});

test('getCoordinatesFromLocationId', function() {
    $board = new \DaveSnake\Models\Board((object) [
        "width" => 11,
        "height" => 11,
        "food" => [],
        "hazards" => [],
        "snakes" => []
    ]);

    $coordinates = $board->getCoordinatesFromLocationId(1);
    expect($coordinates)->toBeInstanceOf(Coordinates::class);
    expect($coordinates->x)->toEqual(0);
    expect($coordinates->y)->toEqual(0);

    $coordinates = $board->getCoordinatesFromLocationId(121);
    expect($coordinates)->toBeInstanceOf(Coordinates::class);
    expect($coordinates->x)->toEqual(10);
    expect($coordinates->y)->toEqual(10);
});

test("getAdjacentCells", function() {
    $board = new \DaveSnake\Models\Board((object) [
        "width" => 11,
        "height" => 11,
        "food" => [],
        "hazards" => [],
        "snakes" => []
    ]);

    $target = new Coordinates((object)[
        "x" => 0,
        "y" => 0,
    ]);

    $adjacents = $board->getAdjacentCells($target);
    expect($adjacents)->toBeArray();
    expect($adjacents)->toHaveKeys(["right", "up"]);

    $target = new Coordinates((object)[
        "x" => 10,
        "y" => 10,
    ]);

    $adjacents = $board->getAdjacentCells($target);
    expect($adjacents)->toBeArray();
    expect($adjacents)->toHaveKeys(["left", "down"]);
});