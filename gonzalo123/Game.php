<?php

class Game
{
    const LINE = 1;
    const BINGO = 2;
    private $players = array();
    private $throw, $winner;
    private $isRunning = false;
    const MAX_NUMBER = 90;

    public function __construct()
    {
        $this->numbers = $this->shuffleNumbers();
        $this->throw = 0;
        $this->isRunning = true;
    }

    public function registerPlayer(Player $player)
    {
        $this->players[] = $player;
    }

    private function shuffleNumbers()
    {
        $numbers = range(1, self::MAX_NUMBER);
        shuffle($numbers);
        return $numbers;
    }

    public function extractBall()
    {
        if ($this->weStillHAveNumbersAvailable()) {
            foreach ($this->players as $player) {
                list($event, $eventPlayer) = $this->iterateOverPlayer($player);
            }
            $this->throw++;
        } else {
            $this->isRunning = false;;
        }
        return array('event' => $event, 'player' => $eventPlayer);
    }

    private function iterateOverPlayer($player)
    {
        $eventPlayer = null;
        $event = $player->checkNumber($this->numbers[$this->throw]);

        switch ($event) {
            case self::LINE:
                $eventPlayer = $player->getPlayerName();
                break;
            case self::BINGO:
                $eventPlayer  = $player->getPlayerName();
                $this->winner = $eventPlayer;
                $this->isRunning = false;
        }
        return array($event, $eventPlayer);
    }

    private function weStillHAveNumbersAvailable()
    {
        return isset($this->numbers[$this->throw]);
    }

    public function playIsRunning()
    {
        return $this->isRunning;
    }

    public function getWinner()
    {
        return $this->winner;
    }
}