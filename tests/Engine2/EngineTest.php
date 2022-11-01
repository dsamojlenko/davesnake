<?php

use DaveSnake\Models\Coordinates;

test("findTargetInArray", function() {
    $board = new \DaveSnake\Models\Board((object) [
        "width" => 11,
        "height" => 11,
        "food" => [],
        "hazards" => [],
        "snakes" => []
    ]);

    $me = new \DaveSnake\Models\BattleSnake((object) [
        "id" => "1",
        "name" => "name",
        "health" => 100,
        "body" => [],
        "latency" => "",
        "head" => new Coordinates((object) ["x" => 1, "y" => 1]),
        "length" => 1,
        "shout" => "",
        "squad" => "",
        "customizations" => (object)[]
    ]);

    $helpers = new \DaveSnake\Engine2\Helpers((object)[
        "board" => $board,
        "you" => $me
    ]);

    $arrayOfLocations = [
        (object) [
            "x" => 1,
            "y" => 1,
        ],
        (object) [
            "x" => 1,
            "y" => 2,
        ],
        (object) [
            "x" => 1,
            "y" => 3,
        ],
        (object) [
            "x" => 1,
            "y" => 4,
        ],
        (object) [
            "x" => 1,
            "y" => 5,
        ],
        (object) [
            "x" => 1,
            "y" => 6,
        ],
    ];

    $target = (object) [
        "x" => 1,
        "y" => 2,
    ];

    $found = $helpers->findTargetInArray($target, $arrayOfLocations);
    expect($found)->toBeTrue();

    $target = (object) [
        "y" => 6,
        "x" => 1,
    ];

    $found = $helpers->findTargetInArray($target, $arrayOfLocations);
    expect($found)->toBeTrue();

    $target = (object) [
        "x" => 22,
        "y" => 6,
    ];

    $found = $helpers->findTargetInArray($target, $arrayOfLocations);
    expect($found)->toBeFalse();
});