<?php

namespace App\Support;

class ResponseBuilder
{
    private $error = false;
    private $message;
    private $data = [];
    private $statusCode = 200;

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

    public function withError()
    {
        $this->error = true;

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

        return response()->json($response, $this->statusCode);
    }
}
