<?php

declare(strict_types=1);

namespace DaveSnake\Models;

class BattleSnake
{
    public string $id;
    public string $name;
    public int  $health;
    public array $body;
    public string $latency;
    public Coordinates $head;
    public integer $length;
    public string $shout;
    public string $squad;
    public object $customizations;
}