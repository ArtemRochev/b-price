<?php

namespace App\DTO;

class ExchangePair
{
    private string $from;

    private string $to;

    public function __construct(string $pair)
    {
        [$this->from, $this->to] = explode('/', $pair);
    }

    public function getFrom(): string
    {
        return $this->from;
    }

    public function getTo(): string
    {
        return $this->to;
    }
}
