<?php

class Player
{
    private $playerName, $card, $playBoard, $lineas;
    private $allNumbers = array();

    public function __construct($playerName, Card $card)
    {
        $this->playerName = $playerName;
        $this->card       = $card;

        $this->playBoard = array(0, 0, 0);
        $this->lineas = 0;
        $this->cardNumbers = $this->card->getCardNumbers();
        $this->populateAllNumbers();
    }

    private function populateAllNumbers()
    {
        foreach ($this->cardNumbers as $number => $row) {
            $this->allNumbers[$number] = $row;
        }
    }

    public function getPlayerName()
    {
        return $this->playerName;
    }

    public function checkNumber($number)
    {
        if (array_key_exists($number, $this->allNumbers)) {
            $rowId = $this->allNumbers[$number];
            $this->increasesTheNumberOfNumbersPerLine($rowId);
            if ($this->userHasAllNumbersWithinOnLine($rowId)) {
                $this->lineas++;
                return $this->isCardComplete() ? Game::BINGO : Game::LINE;
            }
        }
        return null;
    }

    private function isCardComplete()
    {
        return $this->lineas == Card::NUMBER_OF_ROWS;
    }

    private function userHasAllNumbersWithinOnLine($rowId)
    {
        return $this->playBoard[$rowId] == Card::NUMBER_OF_COLS - Card::NUMBER_OF_BLANKS_PER_ROW;
    }

    private function increasesTheNumberOfNumbersPerLine($rowId)
    {
        $this->playBoard[$rowId]++;
    }

    public function getCard()
    {
        return $this->card;
    }
}