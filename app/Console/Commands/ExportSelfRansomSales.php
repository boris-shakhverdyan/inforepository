<?php

namespace App\Console\Commands;

use App\Exports\SalesSelfRansomsExport;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use Exception;

class ExportSelfRansomSales extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export:sale-self-ransoms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export all self ransom sales';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        try {
            $this->info("Starting to export");

            $this->newLine();

            Excel::store(new SalesSelfRansomsExport(), "self-ransom-sales.csv", "exports", \Maatwebsite\Excel\Excel::CSV);

            $this->info("Successfully exported");
        } catch (Exception $e) {
            $this->error($e->getMessage());
            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
