<?php

namespace SDMTests\FizzBuzz;

use PHPUnit\Framework\TestCase;
use SDM\FizzBuzz\FizzBuzz;

class FizzBuzzTest extends TestCase
{
    /**
     * @var FizzBuzz
     */
    private $app;

    public function setUp()
    {
        $this->app = new FizzBuzz();
    }

    /**
     * @test
     */
    public function can_run_fizzbuzz(): void
    {
        $this->assertSame('1', $this->app->getResults(1));
        $this->assertSame('Fizz', $this->app->getResults(3));
        $this->assertSame('Buzz', $this->app->getResults(5));
        $this->assertSame('FizzBuzz', $this->app->getResults(15));
        $this->assertSame('16', $this->app->getResults(16));
    }

    /**
     * @test
     */
    public function can_run_fizzbuzz_with_1_3(): void
    {
        $fizz = 1;
        $buzz = 3;
        $this->assertSame('Fizz', $this->app->getResults(1, $fizz, $buzz));
        $this->assertSame('Fizz', $this->app->getResults(2, $fizz, $buzz));
        $this->assertSame('FizzBuzz', $this->app->getResults(3, $fizz, $buzz));
    }

    /**
     * @test
     */
    public function can_run_fizzbuzz_with_2_4(): void
    {
        $fizz = 2;
        $buzz = 4;
        $this->assertSame('1', $this->app->getResults(1, $fizz, $buzz));
        $this->assertSame('Fizz', $this->app->getResults(2, $fizz, $buzz));
        $this->assertSame('3', $this->app->getResults(3, $fizz, $buzz));
        $this->assertSame('FizzBuzz', $this->app->getResults(4, $fizz, $buzz));
    }

    /**
     * @test
     */
    public function should_throw_a_exeception_when_testnumber_is_below_1(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->app->getResults(0);
    }

    /**
     * @test
     */
    public function should_throw_a_exeception_when_testnumber_is_above_100(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->app->getResults(101);
    }
}
