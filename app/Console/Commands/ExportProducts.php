<?php

namespace App\Console\Commands;

use App\Exports\ProductsExport;
use Exception;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class ExportProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export:products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export all products';

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

            Excel::store(new ProductsExport(), "products.csv", "exports", \Maatwebsite\Excel\Excel::CSV);

            $this->info("Successfully exported");
        } catch (Exception $e) {
            $this->error($e->getMessage());
            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
