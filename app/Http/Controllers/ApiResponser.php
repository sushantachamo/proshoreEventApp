<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

/**
 * Trait ApiResponser
 * @package App\Http\Controllers
 */
trait ApiResponser
{
    /**
     * Build success responses
     *
     * @param string|array $data
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    public function successResponse($data = null, $message = "OK", $code = Response::HTTP_OK)
    {
        return new JsonResponse(["success" => true, "message" => $message, "data" => $data], $code);
    }
    /**
     * Build error responses
     *
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    public function errorResponse($message, $code = Response::HTTP_INTERNAL_SERVER_ERROR)
    {
        return new JsonResponse(["success" => false, "message" => $message], $code);
    }

    /**
     * Build error responses with info
     *
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    public function errorResponseWithInfo($message, $code = Response::HTTP_INTERNAL_SERVER_ERROR, $info = [])
    {
        return new JsonResponse(["success" => false, "message" => $message, 'info' => $info], $code);
    }

    /**
     * Respond failure with errors
     *
     * @param $message
     * @param array $errors
     * @param int $code
     * @return JsonResponse
     */
    public function errorResponseWithErrors($message, $errors = [], $code = Response::HTTP_INTERNAL_SERVER_ERROR)
    {
        return new JsonResponse(["success" => false, "errors" => $errors, "message" => $message], $code);
    }

    /**
     * Build error responses with data
     *
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    public function errorResponseWithData($message, $code = Response::HTTP_BAD_REQUEST, $info = [])
    {
        return new JsonResponse(["success" => false, "message" => $message, 'errors' => $info], $code);
    }
}
