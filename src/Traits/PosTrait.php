<?php

namespace Kuzry\Przelewy24\Traits;

trait PosTrait
{
    protected string $pos = 'default';

    public function setPos(string $pos): self
    {
        $this->pos = $pos;
        return $this;
    }
}
