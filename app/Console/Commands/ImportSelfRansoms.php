<?php

namespace App\Console\Commands;

use App\Imports\SalesImport;
use App\Imports\SelfRansomsImport;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use Exception;

class ImportSelfRansoms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:self-ransoms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import self ransom sales';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        try {
            $this->info("Starting to import");

            $this->newLine();

            Excel::import(new SelfRansomsImport(), "self-ransom-sales.csv", "exports", \Maatwebsite\Excel\Excel::CSV);

            $this->info("Successfully imported");
        } catch (Exception $e) {
            $this->error($e->getMessage());
            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
