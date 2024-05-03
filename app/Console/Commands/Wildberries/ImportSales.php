<?php

namespace App\Console\Commands\Wildberries;

use App\Models\Product;
use App\Models\Sale;
use App\Services\Wildberries\WBApi;
use Illuminate\Console\Command;
use Exception;

class ImportSales extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wb:import-sales';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import sales from Wildberries';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $api = new WBApi();

        try {
            $response = $api->getSalesList();

            if (!$response->successful()) {
                $this->error("Something went wrong!");
                return self::FAILURE;
            }

            $this->info("Sales received!");
            $this->newLine();

            $body = $response->json();

            $sales = $body["orders"];

            foreach ($sales as $sale) {
                if (Sale::findByWbSaleId($sale["id"])) {
                    continue;
                }

                $product = Product::findByVendorCode($sale["article"]);

                $item = new Sale();
                $item->type = Sale::TYPE_SALE;
                $item->wb_sale_id = $sale["id"];
                $item->wb_price = substr($sale["convertedPrice"] . "", 0, -2);
                $item->warehouse_tax = Sale::DEFAULT_WAREHOUSE_TAX;
                $item->product = $product ? Sale::productToArray($product) : null;
                $item->note = null;
                $item->sell_date = $sale["createdAt"];
                $item->save();
            }

            $this->info("Sales successfully imported!");
        } catch (Exception $e) {
            $this->error($e->getMessage());
            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
