<?php

namespace SDM\Import;

class Import implements ImportInterface
{
    /**
     * @var string
     */
    private $filePath;

    /**
     * @var string
     */
    private $delimiter;

    /**
     * @var ConfigurableProductInterface[]
     */
    private $configurableProducts = [];

    /**
     * @var SimpleProductInterface[]
     */
    private $simpleProducts = [];

    /**
     * Importer.
     *
     * @param string $filePath  The path to the csv file
     * @param string $delimiter CSV delimter
     */
    public function __construct(string $filePath, string $delimiter = ',')
    {
        $this->filePath = $filePath;
        $this->delimiter = $delimiter;
    }

    /**
     * Parse the csv file.
     */
    public function parse(): void
    {
        $handle = fopen($this->filePath, 'rb');
        $lineNumber = 0;
        $headers = ['sku', 'title', 'attributes', 'stock', 'price'];

        while (false !== ($line = fgetcsv($handle, null, $this->delimiter))) {
            if (1 === ++$lineNumber) {
                continue;
            }

            $data = array_combine($headers, $line);
            if ($this->isConfigurable($data)) {
                $this->createConfigurableProduct($data);
            } else {
                $this->createSimpleProduct($data);
            }
        }
    }

    private function createConfigurableProduct(array $data): void
    {
        $sku = substr($data['sku'], 0, strpos($data['sku'], '-'));
        if (!isset($this->configurableProducts[$sku])) {
            $this->configurableProducts[$sku] = (new ConfigurableProduct())
                ->setTitle($data['title'])
                ->setAttributes($this->createAttributes($data['attributes']))
                ->setPrice($data['price'])
                ->setSku($sku)
                ->setStock($data['stock'])
                ->setVisible(true);
        }

        $this->configurableProducts[$sku]->addSimpleProduct($this->createSimpleProduct($data, $sku, false));
    }

    private function isConfigurable(array $data): bool
    {
        return false !== strpos($data['sku'], '-');
    }

    private function createSimpleProduct(array $data, ?string $sku = null, bool $visible = true): SimpleProductInterface
    {
        $product = (new SimpleProduct())
            ->setSku($sku ?: $data['sku'])
            ->setTitle($data['title'])
            ->setAttributes($this->createAttributes($data['attributes']))
            ->setPrice($data['price'])
            ->setVisible($visible)
            ->setStock($data['stock']);
        $this->simpleProducts[] = $product;

        return $product;
    }

    private function createAttributes(string $data): ?array
    {
        $attributes = [$data];
        if (false !== strpos($data, ';')) {
            $attributes = explode(';', $data);
        }

        $outputAttributes = [];
        foreach ($attributes as $attribute) {
            if (false !== strpos($attribute, ':')) {
                [$key, $value] = explode(':', $attribute);
                $outputAttributes[] = [$key => $value];
            }
        }

        return $outputAttributes ?: null;
    }

    /**
     * Get products imported.
     *
     * @return ProductInterface[]
     */
    public function getProducts(): array
    {
        return array_merge($this->configurableProducts, $this->simpleProducts);
    }

}
