<?php

abstract class Exceptions_Statuscode extends Exception {
    public readonly int $status_code;

    public function __construct(int $statuscode, string $message = "", int $code = 0,
                                ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->status_code = $statuscode;
    }
}