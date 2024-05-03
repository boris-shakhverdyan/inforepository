<?php

namespace App\Exports;

use App\Models\Product;
use App\Models\Sale;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithMapping;

class SalesExport implements FromCollection, WithMapping
{
    /**
    * @return Collection
    */
    public function collection(): Collection
    {
        return Sale::all();
    }

    /**
     * @param Sale $row
     * @return array
     */
    public function map($row): array
    {
        return [
            $row->product["wb_id"],
            $row->product["vendor_code"],
            $row->product["production_cost"],
            $row->product["retail_cost"],

            $row->type,
            $row->wb_sale_id,
            $row->wb_price,
            $row->warehouse_tax,
            $row->logistic_tax,
            $row->note,
            $row->sell_date,
        ];
    }
}
