<?php

namespace SDM\Import;

class ConfigurableProduct extends AbstractProduct implements ConfigurableProductInterface
{
    /**
     * @var SimpleProductInterface[]
     */
    private $simpleProducts;

    /**
     * Add simple product to the configurable product.
     *
     * @param SimpleProductInterface $product
     */
    public function addSimpleProduct(SimpleProductInterface $product): void
    {
        $this->simpleProducts[] = $product;
    }

    /**
     * Get the simple products for this configurable product.
     *
     * @return SimpleProductInterface[]
     */
    public function getSimpleProducts(): array
    {
        return $this->simpleProducts;
    }

    /**
     * Get the simple products attributes.
     */
    public function getAttributes(): array
    {
        return parent::getAttributes();
    }

    public function getPrice(): float
    {
        $price = 0;
        foreach ($this->simpleProducts as $product) {
            if (0 === $price || $price > $product->getPrice()) {
                $price = $product->getPrice();
            }
        }

        return $price;
    }
}
