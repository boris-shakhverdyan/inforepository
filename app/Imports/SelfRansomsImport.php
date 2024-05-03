<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Sale;
use Maatwebsite\Excel\Concerns\ToModel;

class SelfRansomsImport implements ToModel
{
    /**
     * @param array $row
     * @return void
     */
    public function model(array $row): void
    {
        $sale = Sale::findByWbSaleId($row[0]);

        if (!$sale) {
            return;
        }

        $sale->type = Sale::TYPE_SELF_RANSOM;
        $sale->note = $row[1] ?: null;

        $sale->save();
    }
}
