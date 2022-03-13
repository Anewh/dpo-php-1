<?php


// Содержит данные о ставке
class Bet
{
    private $gameResultId;
    private $value;
    private $winner;

    public function __construct($gameResultId, $amount, $winner)
    {
        $this->gameResultId = $gameResultId;
        $this->amount = $amount;
        $this->winner = $winner;
    }

    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }
}

// Содержит данные о результате игры
class GameResult
{
    private $id;
    private $winCoeffs;
    private $winner;

    public function __construct($id, $coeffL, $coeffR, $coeffD, $winner)
    {
        $this->id = $id;
        $this->winCoeffs = ['L' => $coeffL, 'R' => $coeffR, 'D' => $coeffD];
        $this->winner = $winner;
    }

    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }
}

/* Считает итоговый баланс игрока на основе ставок и результатах игр
    Входные данные:
    bets - массив данных о ставках
    gameResults - массив данных об играх
    Выходные данные: итоговый баланс игрока
*/
function findBalance($bets, $gameResults): float
{
    $gamerAmount = 0;
    foreach ($bets as $bet) {
        $gamerAmount -= $bet->amount;
    }

    $resultsById = [];
    foreach ($gameResults as $result) {
        $resultsById[$result->id] = $result;
    }

    foreach ($bets as $bet) {
        $result = $resultsById[$bet->gameResultId];

        if ($bet->winner == $result->winner) {
            $gamerAmount += $bet->amount * $result->winCoeffs[$bet->winner];
        }
    }
    return $gamerAmount;
}
