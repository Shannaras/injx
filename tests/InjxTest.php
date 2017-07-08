<?php

namespace Injx\Test;

use PHPUnit\Framework\TestCase;

use Injx\Injx;
use Injx\InjxContainer;

class InjxTest extends TestCase {
    public function setUp() {
        $this -> tested = new class { use Injx; };
        $this -> mockContainer = new InjxContainer(['foo'=>'bar']);
    }
    private function initDescendant() {
        $this -> tested_descendant = $this->tested->injxTo(
            new class { use Injx; }
        );
        $this -> tested -> injxFrom( $this -> mockContainer );
    }
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInjxFromBadContainer() {
        $this -> tested -> injxFrom(new class {});
    }
    public function testInjxToNonContainer() {
        $anObj = new class {};
        $this->assertEquals($anObj, $this -> tested -> injxTo($anObj));
    }
    public function testInjxFromReturnValue() {
        $actual = $this -> tested -> injxFrom( $this -> mockContainer );
        $this -> assertEquals($this -> tested, $actual);
    }
    public function testInjxOkBeforeInjection() {
        $this->assertFalse( $this->tested->injxOk() );
    }
    public function testInjxOkAfterInjection() {
        $this -> tested -> injxFrom( $this -> mockContainer );
        $this->assertTrue( $this->tested->injxOk() );
    }
    /**
     * @expectedException \BadMethodCallException
     */
    public function testInjxGetServiceWithoutPreviousInjxCall() {
        $this -> tested -> getService('foo');
    }
    public function testGetService() {
        $this -> tested -> injxFrom( $this -> mockContainer );
        $this -> assertEquals('bar', $this -> tested -> getService('foo'));
    }
    public function testGetServiceWithDescendants() {
        $this ->initDescendant();
        $actual = $this -> tested_descendant -> getService('foo');
        $this -> assertEquals('bar', $actual);
    }
    public function testSetServiceWithoutPreviousInjxCall() {
        $this -> tested -> setService('foo', 'foobar');
        $this -> assertEquals('foobar', $this -> tested -> getService('foo'));
    }
    public function testSetService() {
        $this -> tested -> injxFrom( $this -> mockContainer );
        $this -> tested -> setService('bar', 'foo');
        $this -> assertEquals('foo', $this -> tested -> getService('bar'));
    }
    public function testSetServiceReturnValue() {
        $this -> tested -> injxFrom( $this -> mockContainer );
        $this->assertEquals(
            $this -> tested,
            $this -> tested -> setService('bar', 'foo')
        );
    }
    public function testSetServiceForOverride() {
        $this -> tested -> injxFrom( $this -> mockContainer );
        $this -> tested -> setService('foo', 'foobar');
        $this -> assertEquals('foobar', $this -> tested -> getService('foo'));
    }
    public function testSetServiceWithDescendants() {
        $this ->initDescendant();
        $this -> tested -> setService('foo', 'foobar');
        $this -> assertEquals('foobar', $this -> tested_descendant -> getService('foo'));
    }
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRaiseWithBadParameter() {
        $this -> tested -> raise('bar');
    }
    public function testRaise() {
        $this -> tested -> injxFrom( $this -> mockContainer );
        $this -> tested -> setService('foobar', 'barfoo');
        $this-> assertEquals( NULL, $this->mockContainer->getService('foobar'), 'Pre-requisite' ); 
        $this-> assertEquals( 1, $this -> tested -> raise ('foobar') );
        $this-> assertEquals( 'barfoo', $this->mockContainer->getService('foobar') );        
    }
    public function testRaiseFromDescendants() {
        $this -> initDescendant();
        $this -> tested -> setService('foobar', 'barfoo');
        $this-> assertEquals( NULL, $this->mockContainer->getService('foobar'), 'Pre-requisite' ); 
        $this-> assertEquals( 2, $this -> tested_descendant -> raise ('foobar') );
        $this-> assertEquals( 'barfoo', $this->mockContainer->getService('foobar') );        
    }
    public function testRaiseSafe() {
        $this -> tested -> injxFrom( $this -> mockContainer );
        $this -> tested -> setService('foo', 'barfoo');
        $this-> assertEquals( 'bar', $this->mockContainer->getService('foo'), 'Pre-requisite' ); 
        $this-> assertEquals( 0, $this -> tested -> raise ('foo') );
        $this-> assertEquals( 'bar', $this->mockContainer->getService('foo') ); 
    }
    public function testRaiseUnsafe() {
        $this -> tested -> injxFrom( $this -> mockContainer );
        $this -> tested -> setService('foo', 'barfoo');
        $this-> assertEquals( 'bar', $this->mockContainer->getService('foo'), 'Pre-requisite' ); 
        $this-> assertEquals( 1, $this -> tested -> raise ('foo', false) );
        $this-> assertEquals( 'barfoo', $this->mockContainer->getService('foo') ); 
    }
    private $tested;
    private $tested_descendant;
    private $mockContainer;
}
