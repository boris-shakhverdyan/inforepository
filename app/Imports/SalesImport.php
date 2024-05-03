<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Sale;
use Maatwebsite\Excel\Concerns\ToModel;

class SalesImport implements ToModel
{
    /**
     * @param array $row
     * @return void
     */
    public function model(array $row): void
    {
        $sale = new Sale();

        $product = Product::findByVendorCode($row[1]);

        $sale->product = [
            "title" => $product->title,
            "wb_id" => $row[0],
            "vendor_code" => $row[1],
            "production_cost" => $row[2],
            "retail_cost" => $row[3],
        ];

        $sale->type = $row[4];
        $sale->wb_sale_id = $row[5];

        $sale->wb_price = $row[6];
        $sale->warehouse_tax = $row[7] ?: null;
        $sale->logistic_tax = $row[8] ?: null;
        $sale->note = $row[9] ?: null;
        $sale->sell_date = $row[10] ?: null;
        $sale->save();
    }
}
