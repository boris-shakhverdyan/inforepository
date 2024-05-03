<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductsExport implements FromCollection, WithMapping
{
    /**
    * @return Collection
    */
    public function collection(): Collection
    {
        return Product::all();
    }

    /**
     * @param Product $row
     * @return array
     */
    public function map($row): array
    {
        return [
            $row->wb_id,
            $row->vendor_code,
            $row->production_cost,
            $row->retail_cost,
            $row->title,
            $row->poster,
        ];
    }
}
