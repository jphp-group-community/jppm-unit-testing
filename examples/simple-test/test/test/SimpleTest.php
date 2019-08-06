<?php


namespace test;


use simple\DataObject;

class SimpleTest{
    /**
     * @var DataObject
     */
    private $data;

    /**
     * @before
     */
    public function beforeTest(){
        $this->data = new DataObject('kek', null);
    }

    /**
     * @test
     */
    public function testObject(){
        assertNull($this->data->getNullable());
        assertEquals($this->data->getString(), 'kek');
    }

    /**
     * @test
     */
    public function incorrectTest(){
        assertNotNull($this->data->getNullable());
        assertEquals($this->data->getString(), 'not_kek');
    }

    /**
     * @after
     */
    public function afterTest(){
        $this->data->dispose();
    }
}