<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class Initialize extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Подготовка к первому запуску приложения';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $selectedDbConf = config('database.default');
        $dbName = config(sprintf('database.connections.%s.database', $selectedDbConf));
        $dbSchema = config(sprintf('database.connections.%s.schema', $selectedDbConf));

        try {
            DB::statement(sprintf("CREATE DATABASE %s", $dbName));
            $this->info(sprintf('Database %s created', $dbName));
        } catch (\Throwable $e) {
            $this->warn($e->getMessage());
        }

        try {
            DB::statement(sprintf("CREATE SCHEMA %s", $dbSchema));
            $this->info(sprintf('Schema %s created in database %s', $dbSchema, $dbName));
        } catch (\Throwable $e) {
            $this->error($e->getMessage());
        }

        return 0;
    }
}
