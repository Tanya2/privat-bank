<?php

namespace PrivatBank;

use GuzzleHttp\Client;

class ExchangedAmount
{
    private $from;
    private $to;
    private $amount;

    public function __construct($from, $to, $amount)
    {
        $this->from = $from;
        $this->to = $to;
        $this->amount = $amount;
    }

    public function toDecimal()
    {
        try {
            $courses = $this->getAllCours();
            foreach ($courses as $cours) {
                if (in_array($this->from, $cours) && in_array($this->to, $cours)) {
                    return $this->calculate($cours);
                }
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        return 'Не найден курс обмена с ' . $this->from . ' на ' . $this->to . PHP_EOL;
    }

    private function getAllCours(): array
    {
        $client = new Client();
        $result = $client->request(
            'GET',
            "https://api.privatbank.ua/p24api/pubinfo?json&exchange&coursid=5"
        );
        if ($result->getStatusCode() != 200) {
            throw new \Exception('Status not 200 from api privatbank');
        }
        return json_decode($result->getBody(), true);
    }

    private function calculate($cours)
    {
        $this->validate($cours);
        if ($this->to == $cours['base_ccy']) {
            return $this->amount * $cours['buy'];
        } else {
            return $this->amount / $cours['sale'];
        }
    }
    private function validate($cours)
    {
        if (
            empty($cours['ccy']) ||
            empty($cours['buy']) ||
            empty($cours['sale']) ||
            empty($cours['base_ccy'])
        ) {
            throw new \Exception('Ошибка в получении курса валют');
        }
    }
}
