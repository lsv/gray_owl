<?php

namespace SDM\Import;

abstract class AbstractProduct implements ProductInterface
{
    /**
     * @var string
     */
    protected $sku;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var bool
     */
    protected $visible;

    /**
     * @var float
     */
    protected $price;

    /**
     * @var int
     */
    protected $stock;

    /**
     * @var array|null
     */
    protected $attributes;

    /**
     * Get the simple product SKU.
     */
    public function getSku(): string
    {
        return $this->sku;
    }

    public function setSku(string $sku)
    {
        $this->sku = $sku;

        return $this;
    }

    /**
     * Get the simple product title.
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the simple products attributes.
     */
    public function getAttributes(): ?array
    {
        return $this->attributes;
    }

    public function setAttributes(?array $attributes)
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * Is the simple product visible.
     */
    public function isVisible(): bool
    {
        return $this->visible;
    }

    public function setVisible(bool $visible)
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * Get the simple product price.
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * If this product is in stock.
     */
    public function isInStock(): bool
    {
        return $this->stock > 0;
    }

    public function setStock(int $stock)
    {
        $this->stock = $stock;

        return $this;
    }
}
