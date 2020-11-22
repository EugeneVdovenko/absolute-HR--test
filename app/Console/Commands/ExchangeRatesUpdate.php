<?php

namespace App\Console\Commands;

use App\Services\ExchangeRateService;
use Illuminate\Console\Command;

class ExchangeRatesUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exchange-rate:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Загружает данные по курсам валют';

    /**
     * @var ExchangeRateService
     */
    protected ExchangeRateService $exchangeRateService;

    /**
     * Create a new command instance.
     *
     * @param ExchangeRateService $exchangeRateService
     *
     * @return void
     */
    public function __construct(ExchangeRateService $exchangeRateService)
    {
        parent::__construct();

        $this->exchangeRateService = $exchangeRateService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->exchangeRateService->update();

        return 0;
    }
}
