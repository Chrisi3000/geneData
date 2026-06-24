<?php

class Exceptions_Unauthorized extends Exceptions_Statuscode {
    public function __construct(string $message = "", int $code = 0,
                                ?Throwable $previous = null) {
        parent::__construct(401, $message, $code, $previous);
    }
}