<?php

namespace App\Services;

use App\Models\Collection;
use App\Models\ExchangeRate;
use Illuminate\Support\Arr;

class CollectionService
{
    protected ExchangeRateService $exchangeRateService;
    protected CurrencyService $currencyService;

    public function __construct(ExchangeRateService $exchangeRateService, CurrencyService $currencyService)
    {
        $this->exchangeRateService = $exchangeRateService;
        $this->currencyService = $currencyService;
    }

    /**
     * Формируем список курсов по валютам коллекции.
     *
     * Т.к. в базе может быть несколько курсов за один день - нужно отсеить повторы.
     *
     * @param $id
     * @param $date
     *
     * @return array
     */
    public  function getExchangeRatesCollection($id, $date): array
    {
        /** @var Collection $col */
        $col = Collection::query()->find($id);
        $filter = [
            'source_currency_id' => $this->currencyService->getCurrencyIdsByCodes(array_unique(array_keys($col->currencies))),
            'target_currency_id' => $this->currencyService->getCurrencyIdsByCodes(array_unique($col->currencies)),
            'date' => $date,
        ];

        /** @var ExchangeRate[] $exchange_rates */
        $exchange_rates = $this->exchangeRateService->list($filter);
        $result = [];
        foreach ($exchange_rates as $rate) {
            $key = sprintf("%d|%d", $rate->source_currency_id, $rate->target_currency_id);
            if (!Arr::has($result, $key)) {
                $result[$key] = [
                    'source' => array_flip($filter['source_currency_id'])[$rate->source_currency_id],
                    'target' => array_flip($filter['target_currency_id'])[$rate->target_currency_id],
                    'rate' => $rate->exchange_rate
                ];
            }
        }

        return array_values($result);
    }

    /**
     * @param $title
     * @param $currencies
     *
     * @return Collection|null
     */
    public function createCollection($title, $currencies): ?Collection
    {
        $col = new Collection();
        $col->title = $title;
        $col->currencies = $currencies;
        if ($col->save()) {
            return $col;
        }

        return null;
    }

    /**
     * @param int $id
     * @param string $comment
     *
     * @return bool
     */
    public function addComment(int $id, string $comment): bool
    {
        $col = Collection::query()->find($id);
        if ($col) {
            $col->comment = $comment;
            return $col->save();
        }

        return false;
    }
}
