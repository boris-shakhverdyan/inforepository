<?php

namespace App\Console\Commands;

use App\Imports\ProductCustomDataImport;
use App\Imports\ProductsImport;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use Exception;

class ImportProductCustomData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:products-custom';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import products custom data';

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

            Excel::import(new ProductCustomDataImport(), "custom-product-data.csv", "exports", \Maatwebsite\Excel\Excel::CSV);

            $this->info("Successfully imported");
        } catch (Exception $e) {
            $this->error($e->getMessage());
            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
