<?php

namespace SDM\Import;

interface ImportInterface
{
    /**
     * Parse the csv file.
     */
    public function parse(): void;

    /**
     * Get products imported.
     *
     * @return ProductInterface[]
     */
    public function getProducts(): array;

}
