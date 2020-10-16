<?php

namespace App\Core;

use Google_Service_Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\InsufficientAuthenticationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Exception;

class FamaResponse extends JsonResponse
{
    /**
     * @param mixed $data
     */
    public function __construct($data)
    {
        $response = $data;
        $status = Response::HTTP_OK;

        if ($data instanceof Exception) {
            $status = $data->getCode() !== 0 ? $data->getCode() : Response::HTTP_INTERNAL_SERVER_ERROR;
            $message = $data->getMessage();
            if ($data instanceof NotFoundHttpException) {
                $status = $data->getStatusCode();
                $message = Response::$statusTexts[Response::HTTP_NOT_FOUND];
            }
            if ($data instanceof InsufficientAuthenticationException) {
                $status = Response::HTTP_UNAUTHORIZED;
                $message = Response::$statusTexts[Response::HTTP_UNAUTHORIZED];
            }
            if ($data instanceof UsernameNotFoundException) {
                $status = Response::HTTP_UNAUTHORIZED;
                $message = Response::$statusTexts[Response::HTTP_UNAUTHORIZED];
            }
            if ($data instanceof Google_Service_Exception) {
                $response = json_decode($data->getMessage());
                if (isset($response->error)) {
                    $status = $response->error->code;
                    $message = $response->error->message;
                }
            }
            $response = [
                'status' => $status,
                'message' => FamaCore::translate($message)
            ];

        } else if (!is_array($data)) {
            $response = (array)$data;

        } else if (is_array($data)) {
            if (isset($data['status'])) {
                $status = $data['status'];
            }
            if (isset($data['fm']) && count($data['fm']) !== 0) {
                $status = Response::HTTP_BAD_REQUEST;
                $response = [
                    'status' => $status,
                    'fm' => $data['fm']
                ];
            }

        }

        parent::__construct($response, $status);
    }
}