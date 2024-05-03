<?php

namespace App\Nova;

use App\Models\Product as Model;
use Laravel\Nova\Fields\Avatar;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class Product extends Resource
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
    public static $title = 'full_title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'title',
        'vendor_code',
        'wb_id',
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

            Avatar::make("Постер", "poster")
                ->squared()->thumbnail(function () {
                    return $this->poster;
                })->exceptOnForms()->preview(function () {
                    return $this->poster;
                })->disableDownload(),

            Text::make("Название", "title")
                ->exceptOnForms(),

            Number::make("Артикул WB", "wb_id")
                ->exceptOnForms(),

            Text::make("Артикул Продавца", "vendor_code")
                ->exceptOnForms(),

            Currency::make("Себестоимость", "production_cost")
                ->sortable(),

            Currency::make("Розничная Цена", "retail_cost")
                ->sortable(),

            HasMany::make("Продажи", "sales", Sale::class)
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
        return [];
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
        return [];
    }

    public static function label(): string
    {
        return "Продукты";
    }

    public static function createButtonLabel(): string
    {
        return "Добавить продукт";
    }

    public static function updateButtonLabel(): string
    {
        return "Изменить продукт";
    }
}
