<?php

if (!function_exists('errorResponse')) {
    /**
     * @param int|null $code
     * @param string|null $message
     */
    function errorResponse(?int $code = 500, ?string $message = 'Ошибка. Попробуйте повторно')
    {
        header("Content-Type: application/json");
        http_response_code($code);

        echo json_encode($message);

        exit;
    }
}

if (!function_exists('handleException')) {
    /**
     * @param Exception $exception
     */
    function handleException(Exception $exception)
    {
        error_log('Exception code ' . $exception->getCode() . ': ' . addslashes($exception->getMessage()));

        errorResponse();
    }
}