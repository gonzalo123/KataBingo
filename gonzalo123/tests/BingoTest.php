<?php
require_once 'Mockery/Loader.php';
require_once 'Hamcrest/Hamcrest.php';
$loader = new \Mockery\Loader;
$loader->register();

include "../Player.php";
include "../Card.php";
include "../Game.php";

use \Mockery as m;

class BingoTest extends PHPUnit_Framework_TestCase
{
    public function testGame()
    {
        $players = array('Player 1', 'Player 2', 'Player 3', 'Player 4');
        $game = new Game();
        foreach ($players as $player) {
            $game->registerPlayer(new Player($player, new Card()));
        }

        do {
            $throw = $game->extractBall();
            switch ($throw['event']) {
                case Game::LINE:
                    $this->assertTrue(in_array($throw['player'], $players), 'LINE');
                    break;
                case Game::BINGO:
                    $this->assertTrue(in_array($throw['player'], $players), 'BINGO');
                    break;
            }
        } while ($game->playIsRunning());

        $this->assertTrue(in_array($game->getWinner(), $players));
    }

    public function testPlayer()
    {
        /** @var Card $card  */
        $card = m::mock('Card');
        $card->shouldReceive('getCardNumbers')->andReturn(array(
                12 => 0, 34 => 0, 41 => 0, 50 => 0, 63 => 0,
                1  => 1, 14 => 1, 64 => 1, 75 => 1, 87 => 1,
                26 => 2, 37 => 2, 57 => 2, 79 => 2, 89 => 2,
            ));

        $player = new Player('Gonzalo', $card);
        $this->assertEquals('Gonzalo', $player->getPlayerName());

        $this->assertNull($player->checkNumber(99));

        $this->assertNull($player->checkNumber(12));
        $this->assertNull($player->checkNumber(34));
        $this->assertNull($player->checkNumber(41));
        $this->assertNull($player->checkNumber(50));
        $this->assertEquals(Game::LINE, $player->checkNumber(63), 'User has completed line 1');

        $this->assertNull($player->checkNumber(98));

        $this->assertNull($player->checkNumber(1));
        $this->assertNull($player->checkNumber(14));
        $this->assertNull($player->checkNumber(64));
        $this->assertNull($player->checkNumber(75));
        $this->assertEquals(Game::LINE, $player->checkNumber(87), 'User has completed line 2');

        $this->assertNull($player->checkNumber(26));
        $this->assertNull($player->checkNumber(37));
        $this->assertNull($player->checkNumber(57));
        $this->assertNull($player->checkNumber(79));
        $this->assertEquals(Game::BINGO, $player->checkNumber(89), 'User has completed the Bingo');
    }

    public function testCreateCard()
    {
        $card = new Card();
        $this->assertEquals(3, count($card->getLayout()), 'Card layout must have 3 lines');

        $layout = $card->getLayout();
        foreach ($layout as $row) {
            $this->assertEquals(9, count($row), 'Card row must have 9 columns');
            $blankItem = $numberItem = 0;
            $numbers = array();
            foreach ($row as $colNumber => $item) {
                if (is_null($item)) $blankItem++;
                if (is_integer($item)) {
                    $numberItem++;
                    $numbers[] = $item;
                }
                $minValue = $colNumber * 10;
                $maxValue = ($colNumber * 10) + 9;
                if (!is_null($item)) {
                    $this->assertTrue(in_array($item, range($minValue, $maxValue)), "row {$colNumber} must have values between {$minValue} to {$maxValue}");
                }
            }
            $this->assertEquals(4, $blankItem, 'Card row must have 4 blaks');
            $this->assertEquals(5, $numberItem, 'Card row must have 5 numbers');
            $this->assertEquals(5, count(array_unique($numbers)), 'Numbers cannot be repeated within one Row');
        }
    }

    public function teardown()
    {
        m::close();
    }
}
