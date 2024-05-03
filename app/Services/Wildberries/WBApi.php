<?php

namespace App\Services\Wildberries;

use Illuminate\Support\Facades\Http;
use \Exception;

class WBApi
{
    const GET_CARDS_LIST = "content/v2/get/cards/list";

    const GET_ORDERS = "api/v3/orders";

    /**
     * @throws Exception
     */
    public function getCardsList(): \GuzzleHttp\Promise\PromiseInterface|\Illuminate\Http\Client\Response
    {
        return Http::withBody('{"settings": { "cursor": { "limit": 100 }, "filter": { "withPhoto": -1 } } }', "application/json")
            ->withHeaders(self::getHeaders())
            ->post(self::getUrl(self::GET_CARDS_LIST));
    }

    /**
     * @throws Exception
     */
    public function getSalesList($next = 0, $limit = 100): \GuzzleHttp\Promise\PromiseInterface|\Illuminate\Http\Client\Response
    {
        return Http::withHeaders(self::getHeaders())
            ->get(self::getUrl(self::GET_ORDERS . "?next=$next&limit=$limit"));
    }

    /**
     * @throws Exception
     */
    public static function getHeaders(): array
    {
        return [
            "Authorization" => self::getToken()
        ];
    }

    /**
     * @throws Exception
     */
    public static function getToken(): string
    {
        $token = config("wildberries.token");

        if (!$token) {
            throw new Exception("Wildberries Auth Token Not Found");
        }

        return $token;
    }

    public static function getUrl($path = ""): string
    {
        return config("wildberries.api") . $path;
    }
}
