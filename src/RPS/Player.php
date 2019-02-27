<?php

namespace SDM\RPS;

class Player
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $hand;

    public function __construct(string $name, string $hand)
    {
        $this->name = $name;
        $this->hand = strtoupper($hand);
    }

    public function getHand(): string
    {
        return $this->hand;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
