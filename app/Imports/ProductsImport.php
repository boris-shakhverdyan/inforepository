<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;

class ProductsImport implements ToModel
{
    /**
     * @param array $row
     * @return void
     */
    public function model(array $row): void
    {
        $product = new Product();

        $product->wb_id = $row[0];
        $product->vendor_code = $row[1];
        $product->production_cost = $row[2];
        $product->retail_cost = $row[3];
        $product->title = $row[4];
        $product->poster = $row[5];

        $product->save();
    }
}
