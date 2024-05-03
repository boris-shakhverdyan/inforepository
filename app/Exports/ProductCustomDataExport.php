<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Database\Eloquent\Collection;

class ProductCustomDataExport implements FromCollection, WithMapping
{
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
            $row->vendor_code,
            $row->wb_id,
            $row->production_cost,
            $row->retail_cost
        ];
    }
}
