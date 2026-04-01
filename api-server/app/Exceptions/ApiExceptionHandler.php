<?php

namespace App\Exceptions;

use App\Traits\ApiResponses;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Throwable;

class ApiExceptionHandler
{
    use ApiResponses;

    public function handleException(Throwable $e, Request $request): JsonResponse
    {
        if ($e instanceof ValidationException) {
            return $this->handleValidationException($e);
        }

        if ($e instanceof ModelNotFoundException) {
            return $this->handleModelNotFoundException($e);
        }

        if ($e instanceof AuthenticationException) {
            return $this->handleAuthenticationException($e);
        }

        if ($e instanceof AuthorizationException) {
            return $this->handleAuthorizationException($e);
        }

        if ($e instanceof AccessDeniedHttpException) {
            return $this->handleAccessDeniedException($e);
        }

        if ($e instanceof ThrottleRequestsException) {
            return $this->handleThrottleRequestsException($e);
        }

        return $this->handleGenericException($e);
    }

    protected function handleValidationException(ValidationException $e): JsonResponse
    {
        $errors = [];
        foreach ($e->errors() as $key => $messages) {
            foreach ($messages as $message) {
                $errors[] = [
                    'status' => 422,
                    'message' => $message,
                    'source' => $key,
                ];
            }
        }

        return $this->error($errors, 422);
    }

    protected function handleModelNotFoundException(ModelNotFoundException $e): JsonResponse
    {
        return $this->error([
            [
                'status' => 404,
                'message' => 'The resource cannot be found.',
                'source' => $e->getModel(),
            ],
        ], 404);
    }

    protected function handleAuthenticationException(AuthenticationException $e): JsonResponse
    {
        return $this->error([
            [
                'status' => 401,
                'message' => 'Unauthenticated',
                'source' => '',
            ],
        ], 401);
    }

    protected function handleGenericException(Throwable $e): JsonResponse
    {
        return $this->error([
            [
                'type' => class_basename($e),
                'status' => 500,
                'message' => $e->getMessage(),
                'source' => 'Line: '.$e->getLine().' in '.$e->getFile(),
            ],
        ], 500);
    }

    protected function handleAuthorizationException(AuthorizationException $e): JsonResponse
    {
        return $this->error([
            [
                'status' => 403,
                'message' => 'Forbidden',
                'source' => $e->getMessage(),
            ],
        ], 403);
    }

    protected function handleAccessDeniedException(AccessDeniedHttpException $e): JsonResponse
    {
        return $this->error([
            [
                'status' => 403,
                'message' => 'Forbidden',
                'source' => $e->getMessage(),
            ],
        ], 403);
    }

    protected function handleThrottleRequestsException(ThrottleRequestsException $e): JsonResponse
    {
        return $this->error([
            [
                'status' => 429,
                'message' => $e->getMessage() ?: 'Too Many Attempts.',
                'source' => '',
            ],
        ], 429);
    }
}
