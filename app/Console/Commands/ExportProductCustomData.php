<?php

namespace App\Console\Commands;

use App\Exports\ProductCustomDataExport;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use Exception;

class ExportProductCustomData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export:product-custom';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export custom product data';

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

            Excel::store(new ProductCustomDataExport(), "custom-product-data.csv", "exports", \Maatwebsite\Excel\Excel::CSV);

            $this->info("Successfully exported");
        } catch (Exception $e) {
            $this->error($e->getMessage());
            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
