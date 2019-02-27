<?php

namespace SDM\FizzBuzz;

class FizzBuzz implements FizzBuzzInterface
{
    /**
     * FizzBuzz calculator.
     *
     * @param int $testNumber The number to test
     * @param int $fizz       A number which should be divided to say Fizz
     * @param int $buzz       A number which should be divided to say Buzz
     *
     * @return string
     *
     * @throws \RuntimeException Exception thrown if the test number is above 100
     * @throws \RuntimeException Exception thrown if the test number is below 1
     */
    public function getResults(int $testNumber, int $fizz = 3, int $buzz = 5): string
    {
        if ($testNumber < 1) {
            throw new \RuntimeException("{$testNumber} is below 1");
        }

        if ($testNumber > 100) {
            throw new \RuntimeException("{$testNumber} is above 100");
        }

        if (0 === $testNumber % $fizz && 0 === $testNumber % $buzz) {
            return 'FizzBuzz';
        }

        if (0 === $testNumber % $fizz) {
            return 'Fizz';
        }

        if (0 === $testNumber % $buzz) {
            return 'Buzz';
        }

        return (string) $testNumber;
    }
}
