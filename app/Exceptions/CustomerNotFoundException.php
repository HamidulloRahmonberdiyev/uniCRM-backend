<?php

namespace App\Exceptions;

use Exception;

class CustomerNotFoundException extends Exception
{
    public function __construct(
        string $message = "Customer not found",
        int $code = 404,
        Exception $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function render($request)
    {
        return response()->json([
            'success' => false,
            'message' => $this->getMessage(),
            'errors' => [
                'phone' => [$this->getMessage()]
            ]
        ], $this->getCode());
    }
}
