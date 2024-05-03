<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;

class ProductCustomDataImport implements ToModel
{
    /**
     * @param array $row
     * @return void
     */
    public function model(array $row): void
    {
        $product = Product::findByVendorCode($row[0]);

        if (!$product) {
            $product = Product::findByWbId($row[1]);
        }

        if (!$product) {
            return;
        }

        $product->production_cost = $row[2];
        $product->retail_cost = $row[3];

        $product->save();
    }
}
