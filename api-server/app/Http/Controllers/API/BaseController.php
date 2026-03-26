<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\JsonResponse;

class BaseController
{
    /**
     * For list endpoints: admins see all rows; regular users only rows they created.
     */
    protected function listScopeUserId(User $user): ?int
    {
        return $user->isAdmin() ? null : $user->id;
    }

    // success response method
    public function sendError($error, $errorMessages = [], $code = 404): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if (! empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }

    // return error response

    public function sendResponse($result, $message): JsonResponse
    {
        $response = [
            'success' => true,
            'data' => $result,
            'message' => $message,
        ];

        return response()->json($response, 200);
    }
}
