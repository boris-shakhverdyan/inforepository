<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

/**
 * @property  int          $id
 * @property  string       $type
 * @property  string       $wb_sale_id
 * @property  float        $wb_price
 * @property  float        $warehouse_tax
 * @property  float |null  $logistic_tax
 * @property  object       $product
 * @property  string       $note
 * @property  Carbon       $sell_date
 * @property  Carbon       $created_at
 * @property  Carbon       $updated_at
 *
 * @property Product $productModel
 *
 * @method static Builder onlySales()
 * @method static Builder onlySelfRansoms()
 */
class Sale extends Model
{
    use HasFactory, HasJsonRelationships, LogsActivity;

    const TYPE_SELF_RANSOM = "self-ransom";
    const TYPE_SALE = "sale";

    const DEFAULT_WAREHOUSE_TAX = 80;

    protected $fillable = [
        "type",
        "wb_price",
        "warehouse_tax",
        "logistic_tax",
        "product",
        "note",
        "sell_date",
        "wb_sale_id"
    ];

    protected $casts = [
        "sell_date" => "date",
        "product" => "array",
    ];

    protected $with = ["productModel"];

    public function productModel(): BelongsTo
    {
        return $this->belongsTo(Product::class, "product->vendor_code", "vendor_code");
    }

    public function getProfitAttribute(): float
    {
        $profit = $this->product["retail_cost"] * ((100 - 20) / 100);

        return $profit - $this->product["production_cost"] - $this->warehouse_tax - ($this->logistic_tax ?: 0);
    }

    public static function findByWbSaleId($wb_sale_id): Sale|null
    {
        return self::where("wb_sale_id", $wb_sale_id)->first();
    }

    public function scopeOnlySales(Builder $query): void
    {
        $query->where("type", self::TYPE_SALE);
    }

    public function scopeOnlySelfRansoms(Builder $query): void
    {
        $query->where("type", self::TYPE_SELF_RANSOM);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                "type",
                "wb_price",
                "warehouse_tax",
                "logistic_tax",
                "product",
                "note",
                "sell_date",
                "wb_sale_id"
            ]);
    }

    public static function productToArray(Product $product): array
    {
        return [
            "title" => $product->title,
            "wb_id" => $product->wb_id,
            "vendor_code" => $product->vendor_code,
            "production_cost" => $product->production_cost,
            "retail_cost" => $product->retail_cost,
        ];
    }
}
