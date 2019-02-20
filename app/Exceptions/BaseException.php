<?php
namespace App\Exceptions;

class BaseException extends \Exception{
    public function __construct($message)
    {
        http_response_code($this->code);
        parent::__construct($message, $this->code);
    }

    public function json($message = 'error')
    {
        return json_encode(array('result' => false, 'message' => $message));
    }
}