<?php

require_once "./solve_taskA.php";

/*  Предназначен для парсинга файлов с данными для задачи А.
    Для парсинга нужно сначала вызвать функцию parseFile,
    а затем обращаться к свойствам класса
*/
class TaskAParser
{
    // Входные данные задачи
    private $bets;
    private $gameResults;

    // Выходные данные задачи
    private $gamerBalance;

    public function __construct()
    {
    }

    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }

    // Парсит входные и выходные данные из соответствующих файлов
    public function parseData($inputFileName, $outputFileName)
    {
        $strings = file($inputFileName);

        $i = 1;
        $end = intval($strings[0]) + 1;
        for (; $i < $end; $i++) {
            $this->bets[] = $this->parseBet($strings[$i]);
        }

        $end += intval($strings[$i++]) + 1;
        for (; $i < $end; $i++) {
            $this->gameResults[] = $this->parseGameResult($strings[$i]);
        }

        $this->gamerBalance =
            floatval(file_get_contents($outputFileName));
    }

    private static function parseBet($string): Bet
    {
        $v = explode(' ', trim($string));
        return new Bet(intval($v[0]), intval($v[1]), $v[2]);
    }

    private static function parseGameResult($string): GameResult
    {
        $v = explode(' ', trim($string));
        return new GameResult(
            intval($v[0]),
            floatval($v[1]),
            floatval($v[2]),
            floatval($v[3]),
            $v[4]
        );
    }
}

// Тестирует решение для задачи A
function testTaskA(): bool
{
    for ($i = 1; $i <= 8; $i++) {
        echo "Запущен тест ".$i.": ";

        $parser = new TaskAParser();
        $parser->parseData(
            './tests/00'.$i.'.dat',
            './tests/00'.$i.'.ans'
        );

        $balance = findBalance($parser->bets, $parser->gameResults);

        if (($balance - $parser->gamerBalance) >= 0.000001) {
            echo "Провален\n";
            return false;
        } else {
            echo "Пройден\n";
        }
    }
    return true;
}

if (testTaskA()) {
    echo "Все тесты успешно пройдены\n";
} else {
    echo "Тесты не пройдены\n";
}
