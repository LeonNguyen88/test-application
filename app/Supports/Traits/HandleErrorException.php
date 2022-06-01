<?php

namespace App\Supports\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use \GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


trait HandleErrorException
{
    /**
     * @param \Illuminate\Validation\ValidationException $exception
     * @return \Illuminate\Http\JsonResponse
     */
    public function renderApiResponse(ValidationException $exception): JsonResponse
    {
        return response()->json([
            'code'    => JsonResponse::HTTP_BAD_REQUEST,
            'message' => trans('status.validation'),
            'errors'  => $this->convertApiErrors($exception->errors()),
        ], JsonResponse::HTTP_BAD_REQUEST);
    }

    /**
     * @param $errors
     * @return array
     */
    private function convertApiErrors($errors): array
    {
        $result = [];
        foreach ($errors as $k => $error) {
            $result[] = [
                'field'   => $k,
                'message' => $error,
            ];
        }

        return $result;
    }

    /**
     *
     * @param NotFoundHttpException $exception
     * @return JsonResponse
     */
    public function renderApiNotFoundResponse(NotFoundHttpException $exception): JsonResponse
    {
        return response()->json([
            'code'    => JsonResponse::HTTP_NOT_FOUND,
            'message' => trans('status.not_found'),
            'errors'  => $exception->getMessage(),
        ], JsonResponse::HTTP_NOT_FOUND);
    }



    /**
     * @param \Symfony\Component\HttpKernel\Exception\BadRequestHttpException $exception
     * @return \Illuminate\Http\JsonResponse
     */
    public function renderApiBadRequestResponse(BadRequestHttpException $exception): JsonResponse
    {
        return response()->json([
            'code'    => JsonResponse::HTTP_BAD_REQUEST,
            'message' => trans('status.not_found'),
            'errors'  => $exception->getMessage(),
        ], JsonResponse::HTTP_BAD_REQUEST);
    }

    /**
     * Response server error exception
     *
     * @param $exception
     * @return \Illuminate\Http\JsonResponse
     */
    public function renderServerErrorException($exception): JsonResponse
    {
        return response()->json([
            'code'    => JsonResponse::HTTP_INTERNAL_SERVER_ERROR,
            'message' => null,
            'errors'  => $exception->getMessage(),
        ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
    }
}
