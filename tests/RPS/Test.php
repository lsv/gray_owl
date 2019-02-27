<?php

namespace SDMTests\RPS;

use PHPUnit\Framework\TestCase;
use SDM\RPS\CancelledTournamentException;
use SDM\RPS\InvalidTournamentException;
use SDM\RPS\Player;
use SDM\RPS\RPSTournament;

class Test extends TestCase
{
    public function testPlayer(): void
    {
        $player = new Player('name', 'hand');
        $this->assertSame('name', $player->getName());
        $this->assertSame('HAND', $player->getHand());
    }

    public function validMatchesProvider(): array
    {
        return [
            [
                [
                    new Player('Burgess', 'R'),
                    new Player('Clyde', 'R'),
                ],
                'Burgess',
            ],
            [
                [
                    new Player('Aspen', 's'),
                    new Player('Ginger', 'p'),
                    new Player('Clyde', 'P'),
                    new Player('Carter', 'R'),
                    new Player('Blaze', 'R'),
                    new Player('Daffodil', 'S'),
                    new Player('Harmony', 'R'),
                    new Player('Chandler', 'S'),
                    new Player('Basil', 'R'),
                    new Player('Coral', 'S'),
                    new Player('Clive', 'P'),
                    new Player('Alma', 'R'),
                    new Player('Calvert', 'R'),
                ],
                'Clive',
            ],
            [
                [
                    new Player('Adam', 'P'),
                    new Player('Andrew', 'S'),
                    new Player('Chris', 'r'),
                    new Player('Casey', 'P'),
                    new Player('Cadman', 'R'),
                ],
                'Cadman',
            ],
        ];
    }

    /**
     * @dataProvider validMatchesProvider
     *
     * @param Player[]     $players
     * @param string|false $winner
     *
     * @throws CancelledTournamentException
     * @throws InvalidTournamentException
     */
    public function testValidMatches($players, $winner): void
    {
        $tournament = new RPSTournament($players);
        $this->assertSame($winner, $tournament->getWinner()->getName());
    }

    public function invalidMatchesProvider(): array
    {
        return [
            [
                [
                    new Player('John', 'R'),
                    new Player('Jane', 'E'),
                    new Player('Smith', 'B'),
                    new Player('Mike', 'D'),
                ],
            ],
            [
                [
                    new Player('John', 'R'),
                    new Player('Jane', 'E'),
                ],
            ],
        ];
    }

    /**
     * @dataProvider invalidMatchesProvider
     *
     * @param Player[] $players
     *
     * @throws CancelledTournamentException
     * @throws InvalidTournamentException
     */
    public function testInvalidMatches($players): void
    {
        $this->expectException(InvalidTournamentException::class);
        $tournament = new RPSTournament($players);
        $tournament->getWinner();
    }

    public function cancelledMatchesProvider(): array
    {
        return [
            [
                [new Player('John', 'R')],
            ],
            [
                [],
            ],
        ];
    }

    /**
     * @dataProvider cancelledMatchesProvider
     *
     * @param Player[] $players
     *
     * @throws CancelledTournamentException
     * @throws InvalidTournamentException
     */
    public function testCancelTournament($players): void
    {
        $this->expectException(CancelledTournamentException::class);
        $tournament = new RPSTournament($players);
        $tournament->getWinner();
    }
}
