<?php


namespace simple;


class DataObject{
    private $string;
    private $nullable;

    /**
     * DataObject constructor.
     * @param $string
     * @param $nullable
     */
    public function __construct(string $string, $nullable){
        $this->string = $string;
        $this->nullable = $nullable;
    }


    public function getString(): string{
        return $this->string;
    }
    public function getNullable(){
        return $this->nullable;
    }

    public function dispose(){
        $this->string = null;
        $this->nullable = null;
    }
}