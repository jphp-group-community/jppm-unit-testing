<?php

use unit\assert\Assert;


function assertEquals($excepted, $actual){
    Assert::assertEquals($excepted, $actual);
}

function assertNotEquals($excepted, $actual){
    Assert::assertNotEquals($excepted ,$actual);
}

function assertSame($excepted, $actual){
    Assert::assertSame($excepted ,$actual);
}

function assertNotSame($excepted, $actual){
    Assert::assertNotSame($excepted ,$actual);
}

function assertTrue($value, string $message = 'excepted <true>'){
    Assert::assertTrue($value, $message);
}

function assertFalse($value, string $message = 'excepted <false>'){
    Assert::assertFalse($value, $message);
}

function assertNull($value, string $message = 'excepted <null>'){
    Assert::assertNull($value, $message);
}

function assertNotNull($value){
    Assert::assertNotNull($value);
}
