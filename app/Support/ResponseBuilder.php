<?php

namespace App\Support;

class ResponseBuilder
{
    private $error = false;
    private $errorDetails;
    private $message;
    private $data = [];
    private $statusCode = 200;
    private $trace;
    private $code;

    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    public function setStatusCode(int $statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    public function withError($exception = null)
    {
        $this->error = true;

        if (!is_null($exception)) {
            $this->buildError($exception);
        }

        return $this;
    }

    public function json(bool $empty = false)
    {
        if ($empty) {
            return response()->json([], $this->statusCode);
        }

        $response = [
            'error' => $this->error,
            'status' => $this->statusCode,
            'message' => $this->message,
            'data' => $this->data,
        ];

        if (!is_null($this->errorDetails)) {
            $response['error_details'] = $this->errorDetails;
        }

        if (!is_null($this->trace)) {
            $response['trace'] = $this->trace;
        }

        if (!is_null($this->code)) {
            $response['code'] = $this->code;
        }

        return response()->json($response, $this->statusCode);
    }

    private function buildError($exception)
    {
        $statusCode = 500;

        if (method_exists($exception, 'getStatusCode')) {
            $statusCode = $exception->getStatusCode();
        }

        switch ($statusCode) {
            case 401:
                $this->message = $exception->getMessage();
                break;
            case 403:
                $this->message = 'Forbidden';
                break;
            case 404:
                $this->message = 'Not Found';
                break;
            case 405:
                $this->message = 'Method Not Allowed';
                break;
            case 422:
                $this->message = $exception->original['message'];
                $this->errorDetails = $exception->original['errors'];
                break;
            default:
                $this->message = 'Whoops, looks like something went wrong. Message: ' . $exception->getMessage();
                break;
        }

        if (config('app.debug')) {
            if (method_exists($exception, 'getTrace')) {
                $this->trace = $exception->getTrace();
            }
            if (method_exists($exception, 'getCode')) {
                $this->code = $exception->getCode();
            }
        }

        $this->statusCode = $statusCode;
    }
}
