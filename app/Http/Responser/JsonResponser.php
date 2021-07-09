<?php

namespace App\Http\Responser;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use JsonSerializable;

class JsonResponser
{

     /**
     * Return a new JSON response with paginated data
     *
     * @param int $status
     * @param StaffStrength\ApiMgt\Http\Collections\ApiPaginatedCollection $data
     * @param string|null $message
     * @return Illuminate\Http\JsonResponse
     */
    public static function sendPaginated(
        int $status,
        $data = [],
        string $message = ""
    ): JsonResponse {
        $data = $data->toArray();
        $response = [
            "status" => $status,
            "data" => $data['data'],
            "meta" => $data['meta'],
            "message" => ucwords($message),
        ];
        return response()->json($response, $status);
    }

    /**
     * Return a new JSON response without paginated data
     * 
     * @param int $status
     * @param string|null $message
     * @return Illuminate\Http\JsonResponse
     */
    public static function send(
        bool $error = true,
        string $message = "",
        $data = [],
        $statusCode = 200
    ): JsonResponse
    {
        return response()->json(["error" => $error, "message" => $message, "data" => $data], $statusCode);
    }

}
