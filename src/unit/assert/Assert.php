<?php


namespace unit\assert;


class Assert{
    static function assertEquals($excepted, $actual){
        if($excepted != $actual){
            self::fail("excepted {$excepted}, but was {$actual}");
        }
    }

    static function assertNotEquals($excepted, $actual){
        if($excepted == $actual){
            self::fail("expected not equal, but was {$actual}");
        }
    }

    static function assertSame($excepted, $actual){
        if($excepted !== $actual){
            self::fail("excepted {$excepted}, but was {$actual}");
        }
    }

    static function assertNotSame($excepted, $actual){
        if($excepted === $actual){
            self::fail("expected not equal, but was {$actual}");
        }
    }


    static function assertTrue($value, string $message = 'excepted <true>'){
        if($value !== true){
            self::fail($message);
        }
    }

    static function assertFalse($value, string $message = 'excepted <false>'){
        if($value !== true){
            self::fail($message);
        }
    }

    static function assertNotNull($value, string $message = 'excepted not <null>'){
        if($value === null){
            self::fail($message);
        }
    }

    static function assertNull($value, string $message = 'excepted <null>'){
        if($value !== null){
            self::fail($message);
        }
    }


    private static function fail(string $message){
        AssertionFailedError::fail($message);
    }
}