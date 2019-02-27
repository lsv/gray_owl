<?php

namespace SDM\RPS;

class RPSTournament
{
    private const ROCK = 'R';
    private const PAPER = 'P';
    private const SCISSOR = 'S';

    private static $beatTable = [
        self::ROCK => self::SCISSOR,
        self::PAPER => self::ROCK,
        self::SCISSOR => self::PAPER,
    ];

    /**
     * @var Player[]
     */
    private $players;

    /**
     * @param Player[] $players Array with players
     */
    public function __construct(array $players)
    {
        $this->players = $players;
    }

    /**
     * Get the winner of the tournament.
     *
     * @throws InvalidTournamentException
     * @throws CancelledTournamentException
     *
     * @return Player
     */
    public function getWinner(): Player
    {
        if (count($this->players) < 2) {
            throw new CancelledTournamentException('Less than 2 players');
        }

        return $this->calculateWinner($this->players);
    }

    /**
     * @param array $players
     *
     * @return Player
     *
     * @throws InvalidTournamentException
     */
    private function calculateWinner(array $players): Player
    {
        if (1 === count($players)) {
            return $players[0];
        }

        $matches = array_chunk($players, 2);
        $winners = [];
        foreach ($matches as $match) {
            $winners[] = $this->getMatchWinner($match);
        }

        return $this->calculateWinner($winners);
    }

    /**
     * @param Player[] $match
     *
     * @return Player
     *
     * @throws InvalidTournamentException
     */
    private function getMatchWinner(array $match): Player
    {
        if (1 === count($match)) {
            return $match[0];
        }

        [$playerOne, $playerTwo] = $match;
        $this->validateHand($playerOne);
        $this->validateHand($playerTwo);

        $playerOneHand = $playerOne->getHand();
        $playerTwoHand = $playerTwo->getHand();

        if ($playerOneHand === $playerTwoHand) {
            return $playerOne;
        }

        if (
            $playerOneHand === $playerTwoHand
            || self::$beatTable[$playerOneHand] === $playerTwoHand
        ) {
            return $playerOne;
        }

        return $playerTwo;
    }

    /**
     * @param Player $player
     *
     * @throws InvalidTournamentException
     */
    private function validateHand(Player $player): void
    {
        if (!in_array($player->getHand(), [self::PAPER, self::ROCK, self::SCISSOR], true)) {
            throw new InvalidTournamentException('A player must have a valid hand');
        }
    }
}
