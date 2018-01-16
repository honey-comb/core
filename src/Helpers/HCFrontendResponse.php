<?php

declare(strict_types = 1);

namespace HoneyComb\Core\Helpers;

use Illuminate\Http\JsonResponse;

/**
 * Class HCFrontendResponse
 * @package HoneyComb\Core\Helpers
 */
class HCFrontendResponse
{
    /**
     * @param string $message
     * @param null $data
     * @param null $redirectUrl
     * @return JsonResponse
     */
    public function success(string $message, $data = null, $redirectUrl = null): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'redirectUrl' => $redirectUrl,
        ], JsonResponse::HTTP_OK);
    }

    /**
     * @param string $message
     * @param null $data
     * @param int $status
     * @return JsonResponse
     */
    public function error(string $message, $data = null, int $status = JsonResponse::HTTP_BAD_REQUEST): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => $data,
        ], $status);
    }
}
