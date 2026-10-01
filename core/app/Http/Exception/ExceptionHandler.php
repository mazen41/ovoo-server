<?php

namespace App\Http\Exception;

use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExceptionHandler
{
    /**
     * Handles different types of exceptions for API responses.
     * 
     * @param \Exception $e The exception to be handled.
     * @return \Illuminate\Http\JsonResponse The formatted JSON response based on the exception type.
     */
    public function exceptionShowApi($e)
    {
        $exceptionMessage = $e->getMessage();
        
        //for not found
        if ($e instanceof NotFoundHttpException) {
            return apiResponse('not_found', 'error', [$exceptionMessage], statusCode: 404);
        }

        // if ($e instanceof ValidationUnauthorizedException) {
        //     return apiResponse('restricted', 'error', [$exceptionMessage], statusCode: 403);
        // }
        //for authenticated 
        if ($exceptionMessage === 'Unauthenticated.') {
            $notify[] = 'Unauthorized request';
            return apiResponse('unauthorized', 'error', $notify, statusCode: 401);
        }
        //for validation
        if ($e instanceof ValidationException) {
            $errors = $e->validator->messages()->messages();
            $allErrors = array_reduce($errors, function ($carry, $error) {
                return array_merge($carry, array_values($error));
            }, []);
            return apiResponse('validation_error', 'error', $allErrors);
        }

        return apiResponse('exception', 'error', [$exceptionMessage]);
    }

    /**
     * Handles custom 'Not Found' exceptions and returns a response based on whether the request is for the API or web.
     *
     * @param \Exception $e The exception to be handled.
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse The appropriate response based on request type.
     */
    public function exceptionShowForCustomNotFound($e)
    {
        $exceptionMessage = $e->getMessage();
        $message          = explode("||", $exceptionMessage);
        if (isApiRequest()) {
            $notify[] = trim($message[1]);
            return apiResponse('not_found', 'error', $notify);
        } else {
            $notify[] = ['error', trim($message[1])];
            return back()->withNotify($notify);
        }
    }
}
