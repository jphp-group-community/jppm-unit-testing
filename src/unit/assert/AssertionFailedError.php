<?php


namespace unit\assert;


use Exception;
use RuntimeException;
use Throwable;

final class AssertionFailedError extends RuntimeException{
    public function __construct(?string $message = null, $code = 0, ?Throwable $previous = null){
        parent::__construct($message ?? '', $code, $previous);
    }


    /**
     * @param string|null $message
     * @param Throwable|null $throwable
     * @throws AssertionFailedError
     */
    public static function fail(?string $message, ?Throwable $throwable = null){
        throw new self($message, $throwable);
    }
}