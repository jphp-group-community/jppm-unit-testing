<?php

use packager\Annotations;
use php\io\File;
use php\lang\Environment;
use php\lang\System;
use php\lib\arr;
use php\lib\fs;
use php\lib\str;
use php\util\Regex;

$sources = str::split(System::getProperty('unit_testing.sources'), '|');
$stacktrace = (bool)System::getProperty('unit_testing.stacktrace');

require 'res://unit/.functions.php';


$classes = [];

foreach($sources as $source){
    $files = fs::scan($source, ['extensions' => 'php', 'excludeDirs' => true]);
    /** @var File $file */
    foreach($files as $file){
        $className = fs::pathNoExt(fs::relativize($file, $source));
        $class = new ReflectionClass($className);
        $classes[] = $class;
    }
}


/** @var ReflectionClass $clazz */
foreach($classes as $clazz){
    /** @var ReflectionMethod[] $before */
    $before = [];
    /** @var ReflectionMethod[] $after */
    $after = [];
    /** @var ReflectionMethod[] $tests */
    $tests = [];
    foreach($clazz->getMethods(ReflectionMethod::IS_PUBLIC) as $method){
//        if($method->isStatic()){
//            continue;
//        }
        $comment = $method->getDocComment();
        if(!$comment){
            continue;
        }
        $regex = new Regex('\\@([a-z0-9\\-\\_]+)([ ]+(.+))?', 'im', $comment);

        while($regex->find()){
            $groups = $regex->groups();
            $name = $groups[1];
            //$value = $groups[3] ?: true;
            if($name == 'before'){
                $before[] = $method;
            }
            else if($name == 'after'){
                $after[] = $method;
            }
            else if($name == 'test'){
                $tests[] = $method;
            }
        }
    }

    $instance = $clazz->newInstanceArgs([]);


    $failed = [];
    try{
        foreach($before as $beforeMethod){
            $beforeMethod->invoke($instance);
        }

        foreach($tests as $test){
            try{
                $test->invoke($instance);
            }
            catch(Throwable $any){
                $exceptionClass = get_class($any);
                if($stacktrace){
                    echo "Test {$test->getName()} failed with message \"{$any->getMessage()}\"\n";
                    echo "Exception {$exceptionClass}:\n";
                    echo "{$any->getTraceAsString()}\n";
                }
                $failed[] = [$test, $any->getMessage()];
            }
        }

        foreach($after as $afterMethod){
            $afterMethod->invoke($instance);
        }
    }
    finally{
        if(!$failed){
            echo "All tests passed successfully\n";
        }
        else{
            echo "The following tests failed:\n";
            foreach($failed as $fail){
                /** @var ReflectionMethod $method */
                [$method, $message] = $fail;
                echo "{$method->getDeclaringClass()->getName()}::{$method->getName()}, with message:\n";
                echo $message."\n";
            }
        }
    }

}