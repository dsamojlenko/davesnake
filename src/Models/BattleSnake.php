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
    public int $length;
    public string $shout;
    public string $squad;
    public object $customizations;

    public function __construct(object $data)
    {
        $this->id = $data->id;
        $this->name = $data->name;
        $this->health = $data->health;
        $this->body = $data->body;
        $this->latency = $data->latency;
        $this->head = new Coordinates($data->head);
        $this->length = $data->length;
        $this->shout = $data->shout;
        $this->squad = $data->squad;
        $this->customizations = $data->customizations;
    }
}