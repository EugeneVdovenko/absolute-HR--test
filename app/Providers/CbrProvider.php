<?php

namespace App\Providers;

use Carbon\Carbon;

class CbrProvider
{
    protected $url = "https://www.cbr.ru/DailyInfoWebServ/DailyInfo.asmx?WSDL";

    public function __construct()
    {
        try {
            $this->client = new \SoapClient($this->url);
        } catch (\SoapFault $e) {
            dd($e->getMessage());
        }
    }

    public function getExchangeRates(): array
    {
        $response = $this->client->GetCursOnDateXML([
            'On_date' => now()->format('Y-m-d\TH:i:s')
        ]);

        return $this->parseResponse($response->GetCursOnDateXMLResult->any);
    }

    protected function parseResponse($response): array
    {
        $xml = simplexml_load_string($response);
        $result = $xml->xpath("/ValuteData/ValuteCursOnDate");
        $result = array_map(function ($item) {
            return [
                'exchange_rate' => (float) $item->Vcurs / (int) $item->Vnom,
                'source_currency_code' => (string) $item->VchCode,
                'target_currency_code' => 'RUB'
            ];
        }, $result);

        return $result;
    }
}
