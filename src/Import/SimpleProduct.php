<?php

namespace SDM\Import;

class SimpleProduct extends AbstractProduct implements SimpleProductInterface
{
    /**
     * Get the simple product stock.
     */
    public function getStock(): int
    {
        return $this->stock;
    }
}
