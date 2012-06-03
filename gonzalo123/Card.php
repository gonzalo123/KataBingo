<?php
class Card
{
    private $userName, $layout, $numbersPerCol, $cardNumbers;

    const NUMBER_OF_ROWS = 3;
    const NUMBER_OF_COLS = 9;
    const NUMBER_OF_BLANKS_PER_ROW = 4;

    public function __construct()
    {
        $this->numbersPerCol = $this->getNumbersPerCol();
        $this->setLayout();
    }

    public function getLayout()
    {
        return $this->layout;
    }

    public function setLayout()
    {
        for ($rowId = 0; $rowId < self::NUMBER_OF_ROWS; $rowId++) {
            $this->setRowLayout( $rowId);
        }
    }

    private function setRowLayout($rowId)
    {
        $row = array();
        $blanksPerRow = $this->getRandowBlanksPerRow();
        for ($colId = 0; $colId < self::NUMBER_OF_COLS; $colId++) {
            if (in_array($colId, $blanksPerRow)) {
                $row[$colId] = null;
            } else {
                $number = $this->numbersPerCol[$colId][$rowId];
                $row[$colId] = $number;
                $this->cardNumbers[$number] = $rowId;
            }
        }
        $this->layout[] = $row;
    }

    private function getNumbersPerCol()
    {
        $numbers = array();

        for ($colId = 0; $colId < self::NUMBER_OF_COLS; $colId++) {
            $minValue = $colId * 10;
            $maxValue = ($colId * 10) + 9;
            $numbersPerRow = range($minValue, $maxValue);

            foreach (array_rand($numbersPerRow, self::NUMBER_OF_ROWS) as $key) {
                $numbers[$colId][] = $numbersPerRow[$key];
            }
        }
        return $numbers;
    }

    private function getRandowBlanksPerRow()
    {
        return array_rand(range(0, 8), self::NUMBER_OF_BLANKS_PER_ROW);
    }

    public function getCardNumbers()
    {
        return $this->cardNumbers;
    }
}
