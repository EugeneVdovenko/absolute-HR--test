<?php

namespace App\Services;

use App\Models\Currency;
use App\Models\ExchangeRate;
use App\Providers\CbrProvider;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

class ExchangeRateService
{
    /**
     * @var Collection|Currency[]
     */
    protected $currencies;

    /**
     * @var CbrProvider
     */
    protected CbrProvider $provider;

    /**
     * @var CurrencyService
     */
    protected CurrencyService $currencyService;

    public function __construct(CbrProvider $provider, CurrencyService $currencyService)
    {
        $this->provider = $provider;
        $this->currencyService = $currencyService;
    }

    public function update(): bool
    {
        $result = true;
        if ($this->isNeedUpdate()) {
            $this->getCurrencies();
            $response = $this->provider->getExchangeRates();
            $result = $this->hydrateAndSave($response);
        }

        return $result;
    }

    protected function getCurrencies(): Collection
    {
        if (!$this->currencies) {
            $this->currencies = Currency::query()->get()->keyBy('code');
        }

        return $this->currencies;
    }

    protected function isNeedUpdate(): bool
    {
        $lastUpdated = ExchangeRate::query()
            ->where('updated_at', '>', now()->subDay())
            ->first(['updated_at']);

        return is_null($lastUpdated);
    }

    protected function hydrateAndSave($data): bool
    {
        foreach ($data as $currencyInfo) {
            if ($this->validateCurrencyInfo($currencyInfo)) {
                $source_currency_id = $this->currencies->get(Arr::get($currencyInfo, 'source_currency_code'))->id;
                $target_currency_id = $this->currencies->get(Arr::get($currencyInfo, 'target_currency_code'))->id;
                $exchange_rate = Arr::get($currencyInfo, 'exchange_rate');

                $model = new ExchangeRate();
                $model->exchange_rate = $exchange_rate;
                $model->source_currency_id = $source_currency_id;
                $model->target_currency_id = $target_currency_id;
                $model->save();
            }
        }

        return true;
    }

    protected function validateCurrencyInfo(array $data): bool
    {
        return !Validator::make($data, [
            'source_currency_code' => ["required", function ($attribute, $value, $fail) {
                if (!$this->currencies->has($value)) {
                    $fail($attribute.' is invalid.');
                }
            }],
            'target_currency_code' => ["required", function ($attribute, $value, $fail) {
                if (!$this->currencies->has($value)) {
                    $fail($attribute.' is invalid.');
                }
            }],
            'exchange_rate' => ["required", "numeric"],
        ])->fails();
    }
}
