<?php

namespace App\Nova;

use App\Models\Sale as Model;
use App\Nova\Actions\UpdateSalesList;
use App\Nova\Metrics\TotalProfitSales;
use App\Nova\Metrics\TotalSales;
use App\Nova\Metrics\TotalSelfRansoms;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Sale extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<Model>
     */
    public static string $model = Model::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The pagination per-page options configured for this resource.
     *
     * @return array
     */
    public static $perPageOptions = [50, 100, 150];

    public static $perPageViaRelationship = 30;

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->hideFromIndex(),

            BelongsTo::make("Продукт", "productModel", Product::class)->exceptOnForms(),

            BelongsTo::make("Продукт", "productModel", Product::class)->rules(["required"])
                ->onlyOnForms()->fillUsing(function (NovaRequest $request, Model $sale) {
                    $product = \App\Models\Product::find($request->input("productModel"));

                    if ($product) {
                        $sale->product = Model::productToArray($product);
                    }
                }),

            Date::make("Дата продажи", "sell_date")
                ->default(fn () => now())->rules(["required"])->displayUsing(function () {
                    return $this->sell_date->format("d M");
                }),

            Currency::make("Розничная цена", "product->retail_cost")
                ->exceptOnForms(),

            Currency::make("Себестоимость", "product->production_cost")
                ->exceptOnForms()->hideFromIndex(),

            Select::make("Тип", "type")
                ->options([
                    Model::TYPE_SALE => "Продажа",
                    Model::TYPE_SELF_RANSOM => "Самовыкуп",
                ])->default(Model::TYPE_SALE)->onlyOnForms()->rules(["required"]),

            Currency::make("ПВЗ", "warehouse_tax")->rules(["required"])
                ->default(80)->hideFromIndex(),

            Currency::make("Налог Логистики", "logistic_tax")
                ->nullable()->hideFromIndex(),

            Currency::make("Прибыль", "profit")->exceptOnForms(),

            Currency::make("Цена WB", "wb_price")->rules(["required"]),

            Badge::make("Тип", "type")
                ->label(fn ($value) => match ($value) {
                        Model::TYPE_SALE => "Продажа",
                        Model::TYPE_SELF_RANSOM => "Самовыкуп"
                })->map([
                    Model::TYPE_SALE => "success",
                    Model::TYPE_SELF_RANSOM => "warning"
                ])->exceptOnForms()->rules(["required"]),

            Text::make("Номер Задания WB", "wb_sale_id")->onlyOnDetail(),

            Text::make("Заметка", "note")->nullable()
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request): array
    {
        return [
            TotalProfitSales::make()->width("1/4"),
            TotalSales::make()->width("1/4"),
            TotalSelfRansoms::make()->width("1/4")
        ];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request): array
    {
        return [
            UpdateSalesList::make()->standalone()->showInline()
                ->confirmButtonText("Обновить")
                ->cancelButtonText("Отменить")
                ->withoutConfirmation()
        ];
    }

    public static function createButtonLabel(): string
    {
        return "Добавить продажу";
    }

    public static function label(): string
    {
        return 'Продажи';
    }

    public static function updateButtonLabel(): string
    {
        return "Изменить продажу";
    }
}
