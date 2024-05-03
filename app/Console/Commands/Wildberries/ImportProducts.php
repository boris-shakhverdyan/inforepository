<?php

namespace App\Console\Commands\Wildberries;

use App\Models\Product;
use App\Services\Wildberries\WBApi;
use Illuminate\Console\Command;
use Exception;

class ImportProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wb:import-products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import products from wildberries';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $api = new WBApi();

        try {
            $response = $api->getCardsList();

            if (!$response->successful()) {
                $this->error("Something went wrong!");
                return self::FAILURE;
            }

            $this->info("Products received!");
            $this->newLine();

            $body = $response->json();
            $products = $body["cards"];

            foreach ($products as $product) {
                $item = Product::findByWbId($product["nmID"]);

                if (!$item) {
                    $item = new Product();
                    $item->wb_id = $product["nmID"];
                }

                $item->title = trim($product["title"]);
                $item->vendor_code = $product["vendorCode"];
                $item->poster = $product["photos"][0]["big"] ?? null;
                $item->save();
            }

            $this->info("Products successfully imported!");
        } catch (Exception $e) {
            $this->error($e->getMessage());
            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
