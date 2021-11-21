<?php

require __DIR__ . '/../vendor/autoload.php';

use PrivatBank\ExchangedAmount;

$exchangedAmountUAH = new ExchangedAmount('USD', 'UAH', 100);
$resultUAH = $exchangedAmountUAH->toDecimal();
$exchangedAmountUSD = new ExchangedAmount('UAH', 'USD', 2675);
$resultUSD = $exchangedAmountUSD->toDecimal();
