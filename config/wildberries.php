<?php

use Illuminate\Support\Facades\Facade;

return [
    "api" => env("WILDBERRIES_API", "https://suppliers-api.wildberries.ru/"),
    'token' => env('WILDBERRIES_TOKEN', null),
];
