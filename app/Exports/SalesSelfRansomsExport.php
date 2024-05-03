<?php

namespace App\Exports;

use App\Models\Sale;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;

class SalesSelfRansomsExport implements FromCollection, WithMapping
{
    public function collection(): Collection
    {
        return Sale::onlySelfRansoms()->get();
    }

    /**
     * @param Sale $row
     * @return array
     */
    public function map($row): array
    {
        return [
            $row->wb_sale_id,
            $row->note
        ];
    }
}
