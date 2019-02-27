<?php

namespace SDMTests\Import;

use PHPUnit\Framework\TestCase;
use SDM\Import\ConfigurableProductInterface;
use SDM\Import\Import;
use SDM\Import\SimpleProductInterface;

class Test extends TestCase
{
    public function test_csv5(): void
    {
        $imported = $this->parseCsvData(__DIR__.'/files/test5.csv', ',');
        $this->assertCount(3, $imported->products, 'Product count');
        $this->assertCount(1, $imported->configurables, 'Configuruable count');
        $this->assertCount(2, $imported->simples, 'Simple count');
        $this->assertCount(1, $imported->visibles, 'Visible simple count');
        $this->assertCount(2, $imported->nonvisibles, 'Non visible simple count');

        $config = $imported->configurables[0];
        $this->assertSame('car', $config->getSku());
        $this->assertSame('Car', $config->getTitle());
        $this->assertSame(1495.0, $config->getPrice());
        $this->assertCount(2, $config->getAttributes());
        $this->assertArrayHasKey('color', $config->getAttributes()[0]);
        $this->assertSame('blue', $config->getAttributes()[0]['color']);
        $this->assertArrayHasKey('size', $config->getAttributes()[1]);
        $this->assertSame('small', $config->getAttributes()[1]['size']);
        $this->assertFalse($config->isInStock());
    }

    public function test_csv4(): void
    {
        $imported = $this->parseCsvData(__DIR__.'/files/test4.csv', ',');
        $this->assertCount(3, $imported->products, 'Product count');
        $this->assertCount(1, $imported->configurables, 'Configuruable count');
        $this->assertCount(2, $imported->simples, 'Simple count');
        $this->assertCount(1, $imported->visibles, 'Visible simple count');
        $this->assertCount(2, $imported->nonvisibles, 'Non visible simple count');

        $config = $imported->configurables[0];
        $this->assertSame('table', $config->getSku());
        $this->assertSame('Table', $config->getTitle());
        $this->assertSame(1495.0, $config->getPrice());
        $this->assertCount(2, $config->getAttributes());
        $this->assertArrayHasKey('color', $config->getAttributes()[0]);
        $this->assertArrayHasKey('size', $config->getAttributes()[1]);
    }

    public function test_csv3(): void
    {
        $imported = $this->parseCsvData(__DIR__.'/files/test3.csv', ',');
        $this->assertCount(3, $imported->products, 'Product count');
        $this->assertCount(1, $imported->configurables, 'Configuruable count');
        $this->assertCount(2, $imported->simples, 'Simple count');
        $this->assertCount(1, $imported->visibles, 'Visible simple count');
        $this->assertCount(2, $imported->nonvisibles, 'Non visible simple count');

        $config = $imported->configurables[0];
        $this->assertSame('table', $config->getSku());
        $this->assertSame('Table', $config->getTitle());
        $this->assertSame(1495.0, $config->getPrice());
        $this->assertCount(1, $config->getAttributes());
        $this->assertArrayHasKey('color', $config->getAttributes()[0]);
    }

    public function test_csv2(): void
    {
        $imported = $this->parseCsvData(__DIR__.'/files/test2.csv', ',');

        $this->assertCount(2, $imported->products, 'Product count');
        $this->assertCount(0, $imported->configurables, 'Configuruable count');
        $this->assertCount(2, $imported->simples, 'Simple count');
        $this->assertCount(2, $imported->visibles, 'Visible simple count');
        $this->assertCount(0, $imported->nonvisibles, 'Non visible simple count');

        $p1 = $imported->simples[0];
        $this->assertSame('simplesku1', $p1->getSku());
        $this->assertSame('Simple Product 1', $p1->getTitle());
        $this->assertSame(75.0, $p1->getPrice());
        $this->assertTrue($p1->isInStock());
        $this->assertSame(15, $p1->getStock());
        $this->assertNull($p1->getAttributes());

        $p2 = $imported->simples[1];
        $this->assertSame('simplesku2', $p2->getSku());
        $this->assertSame('Simple Product 2', $p2->getTitle());
        $this->assertSame(25.15, $p2->getPrice());
        $this->assertFalse($p2->isInStock());
        $this->assertSame(0, $p2->getStock());
        $this->assertNull($p2->getAttributes());
    }

    public function test_csv1(): void
    {
        $imported = $this->parseCsvData(__DIR__.'/files/test1.csv', ',');

        $this->assertCount(16, $imported->products, 'Product count');
        $this->assertCount(4, $imported->configurables, 'Configuruable count');
        $this->assertCount(12, $imported->simples, 'Simple count');
        $this->assertCount(5, $imported->visibles, 'Visible simple count');
        $this->assertCount(11, $imported->nonvisibles, 'Non visible simple count');

        foreach ($imported->configurables as $product) {
            switch ($product->getSku()) {
                case 'table':
                    $this->assertSame(1495.0, $product->getPrice());
                    $this->assertCount(2, $product->getSimpleProducts());
                    $this->assertArrayHasKey('color', $product->getAttributes()[0]);
                    $this->assertTrue($product->isInStock());
                    break;
                case 'socks':
                    $this->assertSame(65.0, $product->getPrice());
                    $this->assertCount(2, $product->getSimpleProducts());
                    $this->assertArrayHasKey('size', $product->getAttributes()[0]);
                    $this->assertTrue($product->isInStock());
                    break;
                case 'chair':
                    $this->assertSame(340.0, $product->getPrice());
                    $this->assertCount(3, $product->getSimpleProducts());
                    $this->assertArrayHasKey('color', $product->getAttributes()[0]);
                    $this->assertTrue($product->isInStock());
                    break;
                case 'shoe':
                    $this->assertSame(1250.0, $product->getPrice());
                    $this->assertCount(4, $product->getSimpleProducts());
                    $this->assertArrayHasKey('color', $product->getAttributes()[0]);
                    $this->assertArrayHasKey('size', $product->getAttributes()[1]);
                    $this->assertTrue($product->isInStock());
                    break;
                default:
                    throw new \InvalidArgumentException('You have created a configurable product with a SKU it shouldnt have!');
            }
        }

        foreach ($imported->visibles as $visible) {
            if ($visible instanceof SimpleProductInterface) {
                $this->assertSame('simplesku', $visible->getSku());
                $this->assertSame(200, $visible->getStock());
                $this->assertNull($visible->getAttributes());
            }
        }
    }

    /**
     * @param string $filename
     * @param string $demiliter
     *
     * @return ImportProducts
     */
    private function parseCsvData($filename, $demiliter): ImportProducts
    {
        $importer = new Import($filename, $demiliter);
        $importer->parse();
        $products = $importer->getProducts();

        $class = new ImportProducts();
        foreach ($products as $product) {
            $class->products[] = $product;

            if ($product->isVisible()) {
                $class->visibles[] = $product;
            } else {
                $class->nonvisibles[] = $product;
            }

            if ($product instanceof SimpleProductInterface) {
                $class->simples[] = $product;
            }

            if ($product instanceof ConfigurableProductInterface) {
                $class->configurables[] = $product;
            }
        }

        return $class;
    }
}
