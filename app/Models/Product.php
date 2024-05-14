<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Rennokki\QueryCache\Traits\QueryCacheable;

/**
 * @property  int     $id
 * @property  string  $title
 * @property  int     $wb_id
 * @property  string  $vendor_code
 * @property  string  $poster
 * @property  Carbon  $created_at
 * @property  Carbon  $updated_at
 * @property  float   $production_cost
 * @property  float   $retail_cost
 *
 * @property  string  $full_title
 */
class Product extends Model
{
    use HasFactory, HasJsonRelationships, LogsActivity, QueryCacheable;

    /**
     * Specify the amount of time to cache queries.
     * Do not specify or set it to null to disable caching.
     *
     * @var int|\DateTime
     */
    public $cacheFor = 3600;

    /**
     * The tags for the query cache. Can be useful
     * if flushing cache for specific tags only.
     *
     * @var null|array
     */
    public $cacheTags = ['products'];

    /**
     * A cache prefix string that will be prefixed
     * on each cache key generation.
     *
     * @var string
     */
    public $cachePrefix = 'products_';

    /**
     * The cache driver to be used.
     *
     * @var string
     */
    public $cacheDriver = 'file';

    protected $fillable = [
        "title",
        "wb_id",
        "vendor_code",
        "poster",
        "production_cost",
        "retail_cost",
    ];

    public function getFullTitleAttribute(): string
    {
        return sprintf("%s [%s]", $this->title, $this->vendor_code);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class, "product->vendor_code", "vendor_code");
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                "title",
                "wb_id",
                "vendor_code",
                "poster",
                "production_cost",
                "retail_cost",
            ]);
    }

    public static function findByWbId(int $wb_id): Product|null
    {
        return self::where("wb_id", $wb_id)->first();
    }

    public static function findByVendorCode(string $vendor_code): Product|null
    {
        return self::where("vendor_code", $vendor_code)->first();
    }
}
