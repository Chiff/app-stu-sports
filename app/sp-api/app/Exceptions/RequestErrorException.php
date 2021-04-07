<?php

namespace App\Exceptions;

use Exception;

class UnsupportedTypeException extends Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message, 500);
    }

    public function __toString(): string
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
